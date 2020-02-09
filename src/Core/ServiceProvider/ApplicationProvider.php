<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider;

use App\Config;
use App\Core\Container\ServiceProvider;
use App\Core\Storage\FileLoader;
use App\Core\Container\Container;
use Tracy\Debugger;

class ApplicationProvider implements ServiceProvider
{

    public function provide(Container $container)
    {
        $this->registerApplicationFileLoader($container);
        $this->registerConfig($container);
    }

    private function registerApplicationFileLoader(Container $container): void
    {
        $container->share(FileLoader::class, function () {
            return new FileLoader(__DIR__ . '/../../..');
        });
    }

    private function registerConfig(Container $container): void
    {
        /** @var FileLoader $loader */
        $loader = $container->get(FileLoader::class);
        $localPath = $loader->config('parameters.local.php');
        $localConfig = file_exists($localPath) ? require $localPath : [];
        $config = new Config(
            $localConfig + (require $loader->config('parameters.php'))
        );
        $container->share(Config::class, function () use ($config) {
            return $config;
        });

        if ($config->get('app.debug')) {
            Debugger::enable(Debugger::DEVELOPMENT);
        }
    }

}