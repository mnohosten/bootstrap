<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider;

use App\Component;
use App\ComponentLoader;
use App\Config;
use App\Core\Container\Container;
use App\Core\Container\ServiceProvider;
use App\Core\Router\LinkGenerator;
use App\Core\Storage\FileLoader;
use App\Core\Template\Template;
use App\UI\Http\Common\Session\UserSession;
use Tracy\Debugger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TemplateServiceProvider implements ServiceProvider
{
    /** @var FileLoader */
    private $loader;
    /** @var Container */
    private $container;

    public function provide(Container $container)
    {
        $this->container = $container;
        $this->loader = $container->get(FileLoader::class);
        $container->share(ComponentLoader::class, function() use ($container){
            return new ComponentLoader(
                $container,
                require $this->loader->config('components.php')
            );
        });
        $container->share(Template::class, function() use ($container) {
            /** @var Config $config */
            $config = $container->get(Config::class);
            $twig = new Environment(
                new FilesystemLoader($this->loader->view()),
                [
                    'cache' => $this->loader->storage('cache/view'),
                    'debug' => $config->get('app.debug'),
                ]
            );
            $twig->addFunction(new TwigFunction('link', function($route, array $args = []) {
                static $linkGenerator;
                if(!isset($linkGenerator)) {
                    /** @var LinkGenerator $linkGenerator */
                    $linkGenerator = $this->container->get(LinkGenerator::class);
                }
                return $linkGenerator->generate($route, $args);
            }));
            $twig->addFunction(new TwigFunction('component', function(string $name, array $data = []) {
                /** @var ComponentLoader $componentLoader */
                $componentLoader = $this->container->get(ComponentLoader::class);
                /** @var Component $component */
                $component = $componentLoader->load($name);
                return $component->render($data);
            }));
            $twig->addFunction(new TwigFunction('user', function () {
                return $this->container->get(UserSession::class);
            }));
            $twig->addFunction(new TwigFunction('flashes', function() {
                /** @var UserSession $userSession */
                $userSession = $this->container->get(UserSession::class);
                return $userSession->getFlashes();
            }));
            $twig->addFunction(new TwigFunction('dump', function(...$args) {
                foreach ($args as $arg) {
                    Debugger::barDump($arg);
                }
            }));
            $twig->addFunction(new TwigFunction('dd', function(...$args) {
                dump($args);
            }));
            return new Template(
                $twig,
                $container->get(UserSession::class)
            );
        });
    }
}