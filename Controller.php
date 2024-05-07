<?php

namespace janm\phpmvc;

use janm\phpmvc\middlewares\BaseMiddleware;
use JetBrains\PhpStorm\NoReturn;

class Controller
{
    public string $layout = 'main';
    public string $action = '';
    /**
     * @var BaseMiddleware[];
     */
    protected array $middlewares = [];

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $param = [])
    {
        return Aplication::$app->view->renderView($view, $param);
    }

    public function request(Request $request)
    {

        if ($request->isPost()) {
            return 'handle submitted data';
        }

        return Aplication::$app->request->getBody();
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }



}