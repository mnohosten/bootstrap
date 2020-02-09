<?php
declare(strict_types=1);

namespace App\Core\Bus;

interface Handler
{
    /**
     * @param Input $input
     * @return mixed
     */
    public function handle(Input $input);

}