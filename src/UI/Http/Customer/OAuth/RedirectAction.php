<?php
declare(strict_types=1);

namespace App\UI\Http\Customer\OAuth;

use App\Core\Router\Route;
use App\UI\Http\Action;
use Overtrue\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\Response;

class RedirectAction implements Action
{

    /** @var SocialiteManager */
    private SocialiteManager $socialiteManager;

    public function __construct(SocialiteManager $socialiteManager)
    {
        $this->socialiteManager = $socialiteManager;
    }

    public function __invoke(Route $route): Response
    {
        return $this->socialiteManager
            ->driver($route->getArgument('provider'))
            ->redirect();
    }


}