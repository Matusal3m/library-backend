<?php
declare (strict_types = 1);

namespace Library\Config;

use Library\Http\Request;
use Library\Http\Response;

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

    private static function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public static function dispatch(string $path)
    {
        $path   = self::normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        foreach (self::$routes as $route) {

            if (
                ! preg_match("#^{$route['path']}$#", $path) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            [$class, $function] = $route['controller'];

            // load controller
            require __DIR__ . '/../App/Http/Controllers/' . $class . '.php';

            $controllerInstance = new $class;

            $controllerInstance->{$function}(new Request, new Response);
        }
    }

    public static function routes()
    {
        return self::$routes;
    }
}
