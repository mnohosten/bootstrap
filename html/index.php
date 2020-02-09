<?php
declare(strict_types=1);

use App\Application;
use App\Core\Container\Factory;

require __DIR__ . '/../bootstrap.php';

$container = (new Factory())
    ->create(require __DIR__ . '/../config/providers.php');
$container->get(Application::class)->run($container);
