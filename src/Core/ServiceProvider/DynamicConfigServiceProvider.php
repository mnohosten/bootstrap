<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider;

use App\Core\Bus\InputBus;
use App\Core\Container\Container;
use App\Core\Container\ServiceProvider;
use App\Core\UserConfig\UserConfig;

class DynamicConfigServiceProvider implements ServiceProvider
{

    public function provide(Container $container)
    {
        $container->share(UserConfig::class, function() use ($container) {
            /** @var InputBus $bus */
            $bus = $container->get(InputBus::class);
            $config = $bus->handle(new \App\Query\Config\UserConfig());
            return new UserConfig($config);
        });
    }
}