<?php

namespace app\core;

use App\core\Application;

class Controller{
    public function render($view){
        return Application::$app->router->renderView($view);
    }
}