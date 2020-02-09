<?php
declare(strict_types=1);

namespace App\UI\Console;

use Webmozart\Assert\Assert;

abstract class Command
{
    protected $args = [];
    protected $argMap = [];

    final function getArgument(string $name)
    {
        Assert::keyExists($this->argMap, $name, "Argument `{$name}` is not mapped in \$argMap array.");
        return $this->args[$this->argMap[$name]] ?? null;
    }

    final public function setArguments(array $args)
    {
        $this->args = $args;
    }

    abstract public function execute(): void;

}