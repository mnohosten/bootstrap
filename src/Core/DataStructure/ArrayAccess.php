<?php
declare(strict_types=1);

namespace App\Core\DataStructure;

interface ArrayAccess extends \ArrayAccess
{
    public function jsonSerialize(): array;
}