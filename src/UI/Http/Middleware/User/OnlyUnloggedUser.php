<?php
declare(strict_types=1);

namespace App\UI\Http\Middleware\User;

use App\Core\Router\LinkGenerator;
use App\UI\Http\Common\Session\UserSession;
use App\UI\Http\Middleware\Middleware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class OnlyUnloggedUser implements Middleware
{
    /** @var UserSession */
    private UserSession $userSession;
    /** @var LinkGenerator */
    private LinkGenerator $linkGenerator;

    public function __construct(
        UserSession $userSession,
        LinkGenerator $linkGenerator
    )
    {
        $this->userSession = $userSession;
        $this->linkGenerator = $linkGenerator;
    }

    public function execute(): ?Response
    {
        if($this->userSession->isLoggedIn()) {
            return new RedirectResponse(
                $this->linkGenerator->generate('order:index')
            );
        }
        return null;
    }
}
