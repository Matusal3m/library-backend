<?php
namespace Http;

use Dice\Dice;
use Http\Request;
use Http\Response;
use Http\Router;

class Dispatcher
{
    private Dice $dice;

    public function __construct()
    {
        $this->dice = new Dice();
    }

    public function dispatch(string $path)
    {
        $path   = Router::normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        foreach (Router::routes() as $route) {
            $pattern = '#^' . preg_replace('/{id}/', '([\w-]+)', $route['path']) . '$#';

            if (
                ! preg_match($pattern, $path, $matches) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            array_shift($matches);

            [$class, $function] = $route['controller'];

            // load controller
            require __DIR__ . '/../' . preg_replace('/\\\\/', '/', $class) . '.php';

            $controllerInstance = $this->dice->create($class);

            $controllerInstance->{$function}(new Request, new Response, $matches);
        }
    }
}
