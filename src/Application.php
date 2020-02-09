<?php
declare(strict_types=1);

namespace App;

use App\Core\Container\Container;
use App\Core\Router\Dispatcher;
use App\Core\Router\Route;
use App\UI\Http\Middleware\Middleware;
use App\UI\Http\ViewTemplate;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class Application
{

    public function run(Container $container): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $container->get(Dispatcher::class);
        /** @var Route $route */
        $route = $dispatcher->dispatch(
            $_SERVER['REQUEST_METHOD'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        $response = new RedirectResponse('/');
        if ($route) {
            $container->share(Route::class, function() use ($route) { return $route; });
            if(is_null($response = $this->executeMiddleware($container, $route))) {
                $response = $this->dispatchRoute($container, $route);
            }
        }
        $response->send();
    }

    protected function executeMiddleware(Container $container, Route $route): ?Response
    {
        foreach ($route->getMiddleware() as $middlewareClass) {
            /** @var Middleware $middleware */
            $middleware = $container->get($middlewareClass);
            if (!$middleware instanceof Middleware) {
                throw new \LogicException("Instance `{$middlewareClass}` is not instance of Middleware.");
            }
            if(
                !is_null($response = $middleware->execute())
                && $response instanceof Response
            ) {
                return $response;
            }
        }
        return null;
    }

    protected function dispatchRoute(Container $container, Route $route): Response
    {
        $args = [];
        $handler = $route->getHandler();
        if (is_array($handler) && $handler[0] == ViewTemplate::class) {
            $args = [$handler[1]];
            $handler = $container->get($handler[0]);
        } else {
            $args[] = $route;
            $handler = $container->get($handler);
        }
        /** @var Response $response */
        $response = call_user_func_array($handler, $args);
        Assert::isInstanceOf($response, Response::class);
        return $response;
    }

}