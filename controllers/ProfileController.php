<?php

namespace app\controllers;
use app\core\Controller;
use app\models\ProfileModel;
use App\core\Application;

if(isset($_COOKIE['user']))
header("Location: /");

class ProfileController extends Controller{
    private $db;
    private $app;
    public function __construct(){
        $this->db = new ProfileModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function login(){
        if($this->app->request->getMethod() === "get"){
            return $this->app->router->renderOnlyView("login");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['username']) && isset($_POST['pass'])){
                return $this->db->login($_POST['username'], $_POST['pass']);
            }else{
                return header("Location: /login");
            }
        }
    }
}