<?php
declare(strict_types=1);

namespace App\Core\Router;

use Webmozart\Assert\Assert;

class Collection
{
    private $names = [];
    private $routes = [];

    public function add(string $name, Route $route)
    {
        Assert::keyNotExists($this->names, $name);
        $this->routes[$route->getMethod()][$name] = $route;
        $this->names[$name] = &$this->routes[$route->getMethod()][$name];
    }

    /**
     * @param string $method
     * @return array|Route[]
     */
    public function getRoutes(string $method): array
    {
        return $this->routes[$method];
    }

    public function getByName(string $name): Route
    {
        Assert::keyExists($this->names, $name);
        return $this->names[$name];
    }

}