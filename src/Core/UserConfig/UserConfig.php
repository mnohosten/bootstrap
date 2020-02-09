<?php
declare(strict_types=1);

namespace App\Core\UserConfig;

use Webmozart\Assert\Assert;

class UserConfig
{

    /** @var array */
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $key)
    {
        Assert::keyExists($this->config, $key);
        return $this->config[$key];
    }

}