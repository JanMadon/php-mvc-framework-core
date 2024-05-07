<?php

namespace janm\phpmvc;

class Response
{
    public function setStatusCode($code): void
    {
        http_response_code($code);
    }

    public function redirect(string $path): void
    {
        header('Location:'. $path);
    }

}