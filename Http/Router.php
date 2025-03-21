<?php
declare (strict_types = 1);

namespace Http;

class Router
{
    private static array $routes = [];

    public static function get(string $path, array $controller): void
    {
        self::$routes[] = [
            'path'       => self::normalizePath($path),
            'controller' => $controller,
            'method'     => 'GET',
        ];
    }

    public static function post(string $path, array $controller): void
    {
        self::$routes[] = [
            'path'       => self::normalizePath($path),
            'controller' => $controller,
            'method'     => 'POST',
        ];
    }

    public static function put(string $path, array $controller): void
    {
        self::$routes[] = [
            'path'       => self::normalizePath($path),
            'controller' => $controller,
            'method'     => 'PUT',
        ];
    }

    public static function delete(string $path, array $controller): void
    {
        self::$routes[] = [
            'path'       => self::normalizePath($path),
            'controller' => $controller,
            'method'     => 'DELETE',
        ];
    }

    public static function patch(string $path, array $controller): void
    {
        self::$routes[] = [
            'path'       => self::normalizePath($path),
            'controller' => $controller,
            'method'     => 'PATCH',
        ];
    }

    public static function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public static function routes()
    {
        return self::$routes;
    }
}
