<?php

namespace App\core;

use app\core\{Request, Router, Response};

use \ExponentPhpSDK\Expo;

class Application
{
    public Router $router;
    public Response $response;
    public Expo $expo;
    public static $ROOT_DIR;
    public Request $request;
    public static Application $app;
    public function __construct($path)
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->expo = \ExponentPhpSDK\Expo::normalSetup();
        $this->router = new Router($this->request, $this->response);
        self::$ROOT_DIR = $path;
        self::$app = $this;
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
