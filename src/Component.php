<?php
declare(strict_types=1);

namespace App;


interface Component
{

    public function render(array $data = []): void;

}