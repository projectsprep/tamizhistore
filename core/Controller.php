<?php

namespace app\core;

use App\core\Application;

class Controller{
    public function render($view, $params=[], $msg=[]){
        return Application::$app->router->renderView($view, $params, $msg);
    }
}