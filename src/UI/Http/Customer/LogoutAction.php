<?php
declare(strict_types=1);

namespace App\UI\Http\Customer;

use App\Core\Router\LinkGenerator;
use App\Core\Router\Route;
use App\UI\Http\Action;
use App\UI\Http\Common\Authorization;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LogoutAction implements Action
{

    /** @var LinkGenerator */
    private $linkGenerator;
    /** @var Authorization */
    private Authorization $authorization;

    public function __construct(
        LinkGenerator $linkGenerator,
        Authorization $authorization
    )
    {
        $this->linkGenerator = $linkGenerator;
        $this->authorization = $authorization;
    }

    public function __invoke(Route $route): Response
    {
        $this->authorization->revokeAuthorization();
        $this->authorization->getUserSession()->addFlash(
            'Byli jste úspěšně odhlášeni.',
            'success'
        );
        return new RedirectResponse($this->linkGenerator->generate('customer:login'));
    }

}