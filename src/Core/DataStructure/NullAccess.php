<?php
declare(strict_types=1);

namespace App\Core\DataStructure;

class NullAccess implements ArrayAccess
{

    public function jsonSerialize(): array
    {
        return [];
    }

    public function offsetExists($offset)
    {
        return true;
    }

    public function offsetGet($offset)
    {
        return null;
    }

    public function offsetSet($offset, $value)
    {
        // void
    }

    public function offsetUnset($offset)
    {
        // void
    }

}
