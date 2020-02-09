<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider;

use App\Config;
use App\Core\Container\Container;
use App\Core\Container\ServiceProvider;
use App\Core\Router\Collection;
use App\Core\Router\LinkGenerator;
use App\Core\Router\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class HttpServiceProvider implements ServiceProvider
{

    public function provide(Container $container)
    {
        $container->share(Collection::class, function () {
            $collection = new Collection();
            foreach (require __DIR__ . '/../../../config/routes.php' as $name => $cfg) {
                $method = $cfg[0];
                $pattern = $cfg[1];
                $handler = $cfg[2];
                $middleware = $cfg[3] ?? [];
                $collection->add($name, new Route($name, $method, $pattern, $handler, $middleware));
            }
            return $collection;
        });
        $container->share(LinkGenerator::class, function() use ($container) {
            /** @var Config $config */
            $config = $container->get(Config::class);
            return new LinkGenerator(
                $container->get(Collection::class),
                $config->get('app.host'),
            );
        });
        $container->share(Session::class, function() {
            return new Session();
        });
        $container->share(Request::class, function () use ($container) {
            $request = Request::createFromGlobals();
            $request->setSessionFactory(function() use ($container) { // we are @internal here :)
                return $container->get(Session::class);
            });
            return $request;
        });
    }


}