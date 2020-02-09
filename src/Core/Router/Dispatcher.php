<?php
declare(strict_types=1);

namespace App\Core\Router;

class Dispatcher
{
    /** @var Collection */
    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function dispatch(string $method, string $path): ?Route
    {
        foreach ($this->collection->getRoutes($method) as $route) {
            if(preg_match($route->getPattern(), $path, $match)) {
                $args = array_filter($match,'is_string',ARRAY_FILTER_USE_KEY);
                $route->setArgs($args);
                return $route;
            }
        }
        return null;
    }
}