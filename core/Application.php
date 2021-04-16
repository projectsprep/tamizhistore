<?php

namespace App\core;

use app\core\{Request, Router, Response};

class Application{
    public Router $router;
    public Response $response;
    public static $ROOT_DIR;
    public Request $request;
    public static Application $app;
    public function __construct($path)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$ROOT_DIR = $path;
        self::$app = $this;
    }

    public function run(){
        echo $this->router->resolve();
    }
}