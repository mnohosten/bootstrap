<?php
declare(strict_types=1);

namespace App\Core\Bus;

class EventName
{

    public static function received(string $inputName): string
    {
        return $inputName . '.received';
    }

    public static function handled(string $inputName): string
    {
        return $inputName . '.handled';
    }

    public static function failed(string $inputName): string
    {
        return $inputName . '.failed';
    }

}