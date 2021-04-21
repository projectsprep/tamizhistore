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
    private $controller;
    public function __construct(){
        $this->db = new ProfileModel();
        $this->app = new Application(dirname(__DIR__));
        $this->controller = new Controller();
    }

    public function login(){
        if($this->app->request->getMethod() === "get"){
            return $this->app->router->renderOnlyView("login");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['username']) && isset($_POST['pass'])){
                if($this->db->login($_POST['username'], $_POST['pass'])){
                    $this->setAccess($_POST['username']);
                    return header("Location: /");
                }else{
                    $array = ["notValid"=>"true"];
                    $json = json_encode($array);
                    return $this->app->router->renderOnlyView("login", $json);
                }
            }else{
                return "somethibg happened";
            }
        }
    }

    public function setAccess($username){
        $token = md5(rand(0, 100));
        // $query = "Insert into login_access set username='$username', access_token='$token'";
        // $result = $this->conn->query($query);
        // if($result){
        //     echo $this->conn->error;
            return setcookie("user", $token, time() + 1000, "/");
        // }else{
            // echo $this->conn->error;
            // return header("Location: /login");
    }

    public function logout(){
        echo setcookie("user", $_COOKIE['user'], time() -1000, "/");
    }
}