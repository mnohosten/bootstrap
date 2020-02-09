<?php
declare(strict_types=1);

namespace App\Core\Container;

class Factory
{
    public function create(array $providerClasses): Container
    {
        $container = new Container();
        $container->share(Container::class, function () use (&$container) {
            return $container;
        });
        foreach ($providerClasses as $providerClass) {
            (new $providerClass)->provide($container);
        }
        return $container;
    }
}