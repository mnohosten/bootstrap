<?php
declare(strict_types=1);

namespace App\Core\DataStructure;

class ArrayList extends \ArrayIterator
{
    /** @var array */
    private $data;

    public function __construct(array $data = [], $flags = 0)
    {
        $this->data = $data;
        parent::__construct($this->data, $flags);
    }

    public function first()
    {
        $clone = clone $this;
        $clone->rewind();
        return $this->current();
    }
}