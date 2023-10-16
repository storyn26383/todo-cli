<?php

function home_storage_path(string $path = ''): string
{
    return $_SERVER['HOME'].'/.'.app('name').'/'.trim($path, '/');
}
