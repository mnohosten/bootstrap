<?php
declare(strict_types=1);

namespace App\Core\Template;

class FileLoader
{
    /** @var string */
    private $basepath;

    public function __construct(string $basepath)
    {
        $this->basepath = rtrim($basepath,'/') . '/';
    }

    public function load(string $path): string
    {
        return $this->basepath . ltrim($path, '/');
    }

}