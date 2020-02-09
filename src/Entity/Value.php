<?php
declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;

abstract class Value
{

    /** @var int */
    protected $id;

    /**
     * Delivery constructor.
     * @param int $id
     */
    public function __construct($id)
    {
        $id = (int)$id;
        $this->validate($id);
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->id();
    }

    public function translation(): string
    {
        return static::translationOfAll()[$this->id()];
    }

    public static function allPossibleValues(): array
    {
        return array_keys(static::translationOfAll());
    }

    protected function validate($id)
    {
        if(!isset(static::translationOfAll()[$id])) {
            throw new InvalidArgumentException(
                "Value `{$id}` is invalid. Allowed values are: " .
                implode(', ', static::translationOfAll())
            );
        }
    }

    abstract public static function translationOfAll(): array;

}