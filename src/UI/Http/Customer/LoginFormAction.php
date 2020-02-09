<?php
declare(strict_types=1);

namespace App\UI\Http\Customer;

use App\Core\Router\LinkGenerator;
use App\Core\Router\Route;
use App\Entity\User\Credentials;
use App\UI\Http\Action;
use App\UI\Http\Common\Authorization;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginFormAction implements Action
{

    /** @var Request */
    private $request;
    /** @var Authorization */
    private $authorization;
    /** @var LinkGenerator */
    private $linkGenerator;

    public function __construct(
        Request $request,
        Authorization $authorization,
        LinkGenerator $linkGenerator
    )
    {
        $this->request = $request;
        $this->authorization = $authorization;
        $this->linkGenerator = $linkGenerator;
    }

    public function __invoke(Route $route): Response
    {
        $this->authorization->login(new Credentials(
            $this->request->request->get('username'),
            $this->request->request->get('password'),
        ));
        return new RedirectResponse(
            $this->linkGenerator->generate('order:index')
        );
    }

}