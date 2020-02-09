<?php
declare(strict_types=1);

namespace App\Core\Router;

use App\Config;

class LinkGenerator
{
    /** @var Collection */
    private $collection;
    /** @var string */
    private string $appHost;

    public function __construct(
        Collection $collection,
        string $appHost = ''
    )
    {
        $this->collection = $collection;
        $this->appHost = $appHost;
    }

    public function generate(string $name, array $args = []): string
    {
        $route = $this->collection->getByName($name);
        $parse = $route->getParse();
        $uri = '';
        foreach ($parse as $parsedItem) {
            foreach ($parsedItem as $item) {
                if(is_array($item)) {
                    $uri .= $args[$item[0]];
                    unset($args[$item[0]]);
                } else {
                    $uri .= $item;
                }
            }
        }
        if(!empty($args)) {
            $uri .= '?' . http_build_query($args);
        }
        return $uri;
    }

    public function absolute(string $name, array $args = [])
    {
        return $this->appHost . $this->generate($name, $args);
    }
}