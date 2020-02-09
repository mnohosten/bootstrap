<?php
declare(strict_types=1);

namespace App\Core\Storage;

class FileLoader
{
    private string $base;

    public function __construct(string $base)
    {
        $this->base = rtrim(realpath($base), '/') . '/';
    }

    public function loadPath(string $path, string $prefix = ''): string
    {
        return $this->base . $prefix . $this->trim($path);
    }

    public function storage(string $path = ''): string
    {
        return $this->loadPath($path, 'storage/');
    }

    public function view(string $path = ''): string
    {
        return $this->loadPath($path, 'resources/view/');
    }

    public function config(string $path = ''): string
    {
        return $this->loadPath($path, 'config/');
    }

    private function trim(string $path): string
    {
        return ltrim($path, '/');
    }
}
