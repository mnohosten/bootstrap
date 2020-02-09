<?php
declare(strict_types=1);

namespace App\Entity;

class Random
{

    public static function id(): string
    {
        return bin2hex(random_bytes(8));
    }

}