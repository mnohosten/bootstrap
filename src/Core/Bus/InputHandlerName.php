<?php
declare(strict_types=1);

namespace App\Core\Bus;

class InputHandlerName
{

    public static function from(Input $input)
    {
        return get_class($input) . 'Handler';
    }

}