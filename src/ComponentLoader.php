<?php
declare(strict_types=1);

namespace App;

use App\Core\Container\Container;
use Webmozart\Assert\Assert;

class ComponentLoader
{
    /** @var array */
    private $map;
    /** @var Container */
    private $container;

    public function __construct(Container $container, array $map)
    {
        $this->map = $map;
        $this->container = $container;
    }

    public function load(string $name): Component
    {
        Assert::keyExists($this->map, $name, "Component with name `{$name}` does not exists.");
        return $this->container->get($this->map[$name]);
    }

}