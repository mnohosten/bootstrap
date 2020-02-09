<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider\Bus;

use App\Core\Bus\Input;
use App\Core\Bus\Handler;
use App\Core\Bus\HandlerFactory;
use App\Core\Container\Container;

class CommandHandlerFactory implements HandlerFactory
{

    /** @var Container */
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(Input $input): Handler
    {
        return $this->container->get(get_class($input) . 'Handler');
    }


}