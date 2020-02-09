<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider;

use App\Core\Bus\InputBus;
use App\Core\Container\Container;
use App\Core\Container\ServiceProvider;
use App\Core\ServiceProvider\Bus\CommandHandlerFactory;
use League\Event\Emitter;

class InputBusServiceProvider implements ServiceProvider
{

    public function provide(Container $container)
    {
        $container->share(Emitter::class, function() {
            $emitter = new Emitter();
            return $emitter;
        });
        $container->share(InputBus::class, function() use ($container) {
            return new InputBus(
                new CommandHandlerFactory($container),
                $container->get(Emitter::class),
            );
        });
    }

}