<?php
declare(strict_types=1);

namespace App\Core\DataStructure;

class ArrayIterator extends \ArrayIterator implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return iterator_to_array($this);
    }
}