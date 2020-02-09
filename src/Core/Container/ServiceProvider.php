<?php
declare(strict_types=1);

namespace App\Core\Container;

interface ServiceProvider
{
    public function provide(Container $container);
}