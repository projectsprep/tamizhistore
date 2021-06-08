<?php

namespace app\core;

class Request
{
    public Router $router;
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = strtolower($path);
        $position = strpos($path, '?') ?? false;

        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }
}
