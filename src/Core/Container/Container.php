<?php
declare(strict_types=1);

namespace App\Core\Container;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Webmozart\Assert\Assert;

class Container
{
    private $map = [];
    private $shared = [];

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->map);
    }

    public function get(string $key)
    {
        if (isset($this->map[$key]) && !($this->map[$key] instanceof \Closure)) {
            return $this->map[$key];
        }
        if (array_key_exists($key, $this->shared) && is_null($this->shared[$key])) {
            return $this->map[$key] = $this->map[$key]();
        }
        return $this->tryToCreateFromReflection($key);
    }

    public function share(string $key, \Closure $closure)
    {
        $this->set($key, $closure);
        $this->shared[$key] = null;
    }

    private function set(string $key, $instance)
    {
        Assert::keyNotExists($this->map, $key, "Instance with `{$key}` is already defined.");
        $this->map[$key] = $instance;
    }

    /**
     * @param string $key
     * @return object
     */
    private function tryToCreateFromReflection(string $key)
    {
        try {
            $reflection = new ReflectionClass($key);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(
                "Undefined service: `{$key}`."
            );
        }
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            return new $key;
        }
        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            if($parameter->allowsNull()) continue;
            if($parameter->getType()->isBuiltin()) {
                throw new LogicException(
                    "Container cannot create {$key} " .
                    "instance because it don't knows parameter: `{$parameter->getName()}`. " .
                    "You can register it with `Container::share` method to successfully build container ."
                );
            }
            $args[$parameter->getName()] = $this->get($parameter->getClass()->getName());
        }

        return $reflection->newInstanceArgs($args);
    }

}