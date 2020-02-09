<?php
declare(strict_types=1);

namespace App\UI\Http\Customer\OAuth;

use App\Command;
use App\Core\Bus\InputBus;
use App\Core\DataStructure\ArrayIterator;
use App\Core\Router\LinkGenerator;
use App\Core\Router\Route;
use App\Entity\Customer\Customer;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Random;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Query\User\ByUsername;
use App\UI\Http\Action;
use App\UI\Http\Common\Authorization;
use Exception;
use Overtrue\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CallbackAction implements Action
{
    /** @var SocialiteManager */
    private SocialiteManager $socialiteManager;
    /** @var Authorization */
    private Authorization $authorization;
    /** @var LinkGenerator */
    private LinkGenerator $linkGenerator;
    /** @var InputBus */
    private InputBus $bus;

    /** @var User */
    private $user;
    /** @var \Overtrue\Socialite\User */
    private $socialData;
    /** @var Response */
    private $response;

    public function __construct(
        InputBus $bus,
        SocialiteManager $socialiteManager,
        Authorization $authorization,
        LinkGenerator $linkGenerator
    )
    {
        $this->socialiteManager = $socialiteManager;
        $this->authorization = $authorization;
        $this->linkGenerator = $linkGenerator;
        $this->bus = $bus;
    }

    public function __invoke(Route $route): Response
    {
        $this->tryLoadSocialData($route);
        $this->tryLoginUser();
        return $this->response;
    }

    /**
     * @param string $username
     */
    private function loadUserFromUsername(string $username): void
    {
        $this->user = $this->bus->handle(new ByUsername($username));
    }

    /**
     * @param Route $route
     */
    private function tryLoadSocialData(Route $route): void
    {
        try {
            $this->socialData = $this->socialiteManager
                ->driver($route->getArgument('provider'))
                ->user();
        } catch (Exception $exception) {
            $this->authorization->getUserSession()
                ->addFlash(
                    sprintf(
                        "Nepodařilo se zjisti vaše přihlašovací údaje " .
                        "přes %s. Můžete použít jinou přihlašovací metodu.",
                        ucfirst($route->getArgument('provider'))
                    ),
                    'danger'
                );
            $this->response = new RedirectResponse($this->linkGenerator->generate('customer:login'));
        }
    }

    private function tryLoginUser(): void
    {
        if ($this->socialData) {
            try {
                $this->loadUserFromUsername($this->socialData->getUsername());
            } catch (EntityNotFound $notFound) {
                $this->registerFromSocial();
            }
            $this->authorization->authorize($this->user);
            $this->response = new RedirectResponse($this->linkGenerator->generate('customer:profile'));
        }
    }

    private function registerFromSocial(): void
    {
        $id = Random::id();
        $this->user = new User(new ArrayIterator([
            'id' => $id,
            'customer_id' => $id,
            'username' => $this->socialData->getEmail(),
            'password' => null,
            'role' => UserRole::GUEST,
        ]));
        $customer = new Customer(new ArrayIterator([
            'id' => $id,
            'contact_person' => $this->socialData->getName(),
            'email' => $this->user->username,
        ]));
        $this->bus->handle(new Command\Customer\Register($customer));
        $this->bus->handle(new Command\User\Store($this->user));
    }

}