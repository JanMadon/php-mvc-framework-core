<?php

namespace janm\phpmvc\middlewares;

use janm\phpmvc\Aplication;
use janm\phpmvc\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];


    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Aplication::isGuest()) {
            if (empty($this->actions) || in_array(Aplication::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}