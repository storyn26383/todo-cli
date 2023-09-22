<?php

namespace App\Commands\Traits;

trait HasPath
{
    private function normalizePath(string $path): string
    {
        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return $this->toAbsolutePath($path);
    }

    private function isAbsolutePath(string $path): bool
    {
        if ($path[0] === '/') {
            return true;
        }

        if (preg_match('#^[a-zA-Z]:\\\\#', $path) || substr($path, 0, 2) === '\\\\') {
            return true;
        }

        return false;
    }

    private function toAbsolutePath(string $path): string
    {
        return getcwd().DIRECTORY_SEPARATOR.$path;
    }
}
