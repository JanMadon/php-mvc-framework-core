<?php

namespace janm\phpmvc;

use janm\phpmvc\exception\NotFoundException;
use JetBrains\PhpStorm\NoReturn;

class Router
{

    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            Aplication::$app->view->renderView($callback);

        }

        if (!is_object($callback)) {

            /** @var Controller $controller */
            $controller = new $callback[0]();
            Aplication::$app->controller = $controller;
            $controller->action = $callback[1];

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;

        }

        return call_user_func($callback, $this->request, $this->response);
    }
}