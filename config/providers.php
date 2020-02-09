<?php
declare(strict_types=1);

return [
    \App\Core\ServiceProvider\ApplicationProvider::class,
    \App\Core\ServiceProvider\InputBusServiceProvider::class,
    \App\Core\ServiceProvider\DynamicConfigServiceProvider::class,
    \App\Core\ServiceProvider\HttpServiceProvider::class,
    \App\Core\ServiceProvider\TemplateServiceProvider::class,
];
