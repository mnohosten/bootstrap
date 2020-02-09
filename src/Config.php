<?php
declare(strict_types=1);

namespace App;

use Webmozart\Assert\Assert;

class Config
{
    /** @var array */
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get(string $key)
    {
        Assert::keyExists($this->config, $key, "Config with identification: `{$key}` is not defined.");
        return $this->config[$key];
    }

}