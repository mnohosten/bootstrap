<?php
declare(strict_types=1);

namespace App\Core\Bus;

interface HandlerFactory
{
    public function create(Input $input): Handler;
}