<?php

namespace app\core;

use app\core\Application;
use app\core\Response;

use function PHPSTORM_META\type;

class Router{
    public Request $request;
    public Response $response;
    public array $routes = [];

    public function __construct($request, $response){
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path, $callback){
        return $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback){
        return $this->routes['post'][$path] = $callback;
    }

    public function resolve(){
        $method = $this->request->getMethod();
        $path = $this->request->getPath();
        $callback = $this->routes[$method][$path] ?? false;

        if($callback === false){
            include_once Application::$ROOT_DIR . "/views/_404.php";
            return $this->response->setStatusCode(404);
        }

        if(is_string($callback)){
            return $this->renderView($callback);
        }

        if(is_array($callback)){
            $callback[0] = new $callback[0]();
        }

        echo call_user_func($callback);
    }

    public function renderView($callback, $params=[]){
        $layout = $this->renderLayout($callback);
        $view = $this->renderOnlyView($callback, $params);
        return str_replace('{{content}}', $view, $layout);
    }

    public function renderLayout($view){
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/main.php";
        return ob_get_clean();
    }

    public function renderOnlyView($view, $params=[]){
        if(!empty($params)){
            // if(type($params) === "array"){
            //     $params = "Hello world";
            // }else if(type($params) === "json"){
                $params = json_decode($params, true);
            // }
        }

        
        // foreach($params as $param){
        //     foreach($param as $key=>$value){
        //         $$key = $value;
        //     }
        // }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }

}