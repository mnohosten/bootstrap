<?php
declare(strict_types=1);

namespace App\Core\Router;

class Route
{

    /** @var string */
    private $method;
    /** @var string */
    private $pattern;
    /** @var string */
    private $handler;
    /** @var array */
    private $args;
    /** @var string */
    private $name;
    /** @var array */
    private static $cache = [];
    /** @var array */
    private $middleware;

    public function __construct(
        string $name,
        string $method,
        string $pattern,
        $handler,
        array $middleware = []
    )
    {
        $this->name = $name;
        $this->method = $method;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->parse();
        $this->middleware = $middleware;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getArgument(string $key)
    {
        return $this->args[$key] ?? null;
    }

    public function setArgs(array $args): void
    {
        if(isset($this->args)) {
            throw new \LogicException("You can define route argument only once.");
        }
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return self::$cache[$this->name]['pattern'];
    }

    public function getParse(): array
    {
        return self::$cache[$this->name]['parse'];
    }

    public function getHandler()
    {
        return $this->handler;
    }

    private function parse()
    {
        if(!isset(self::$cache[$this->name])) {
            self::$cache[$this->name]['parse'] = $parse = (new Parser())->parse($this->pattern);
            $pattern = '';
            foreach ($parse as $parsedItem) {
                foreach ($parsedItem as $item) {
                    $pattern .= is_array($item) ? "(?P<$item[0]>{$item[1]})" : $item;
                }
            }
            self::$cache[$this->name]['pattern'] = "~^{$pattern}$~";
        }
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

}