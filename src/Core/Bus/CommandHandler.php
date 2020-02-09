<?php
declare(strict_types=1);

namespace App\Core\Bus;

interface CommandHandler extends Handler
{
    public function handle(Input $input): void;
}