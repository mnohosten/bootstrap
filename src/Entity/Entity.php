<?php
declare(strict_types=1);

namespace App\Entity;

use App\Core\DataStructure\ArrayIterator;
use App\Core\DataStructure\ArrayObject;

class Entity extends ArrayObject implements \JsonSerializable
{

    private $isFresh = false;

    public function __call($name, $arguments = [])
    {
        return $this->__get($name);
    }


    public function jsonSerialize()
    {
        return $this->data->jsonSerialize();
    }

    public function __set($name, $value)
    {
        if($this->isFresh) {
            $this->data[$name] = $value;
        }
    }

    /**
     * @return $this
     */
    public function getClone()
    {
        $fresh = new static(new ArrayIterator($this->jsonSerialize()));
        $fresh->isFresh = true;
        return $fresh;
    }

}
