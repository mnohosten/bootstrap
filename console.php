<?php
declare(strict_types=1);


use App\Core\Container\Factory;
use App\UI\Console\Command;
use Webmozart\Assert\Assert;

require __DIR__ . '/bootstrap.php';
$commands = require __DIR__ . '/config/commands.php';
$commandNames = array_keys($commands);
if ($argc < 2) {
    $possible = implode("\n", array_map(function ($key) {return "\t{$key}";}, $commandNames));
    echo <<<CMD
Possible command names are:
$possible

CMD;
    echo 'Invalid usage. Use `php console.php <name> [?arguments]`' . PHP_EOL;
    return 1;
}
try {
    Assert::keyExists($commands, $argv[1],"Command name `{$argv[1]}` is not defined.");
} catch (InvalidArgumentException $exception) {
    echo $exception->getMessage() . PHP_EOL;
    return 1;
}

$container = (new Factory())
    ->create(require __DIR__ . '/config/providers.php');

/** @var Command $command */
$command = $container->get($commands[$argv[1]]);
$command->setArguments(array_slice($argv, 2));
$command->execute();