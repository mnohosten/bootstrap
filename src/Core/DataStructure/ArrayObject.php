<?php
declare(strict_types=1);

namespace App\Core\DataStructure;

use ArrayAccess;
use Webmozart\Assert\Assert;

class ArrayObject
{

    /** @var ArrayAccess */
    protected $data;

    public function __construct(ArrayAccess $data)
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        Assert::true($this->data->offsetExists($name), "Property `{$name}` is not accessible.");
        return $this->data[$name];
    }

}