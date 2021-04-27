<?php

namespace app\controllers;
use app\core\Controller;
use app\models\ProfileModel;
use app\core\Application;
use app\models\DB;
session_start();

if(isset($_COOKIE['user']) && isset($_SESSION['user']))
header("Location: /");

// error_reporting(0);

class ProfileController extends Controller{
    private $db;
    private $DB;
    private $app;
    private $controller;
    public function __construct(){
        $this->DB = new DB();
        $this->DB = $this->DB->conn();
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
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
            return setcookie("user", $token, time() + 1000, "/");
        // }else{
            // echo $this->conn->error;
            // return header("Location: /login");
    }

    public function logout(){
        $_SESSION = [];
        session_destroy();
        return setcookie("user", $_COOKIE['user'], time() -1000, "/");
    }

    public function forgotPassword(){
        if($this->app->request->getMethod() === "get"){
            return $this->app->router->renderOnlyView("forgotPassword");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['username'])){
                $query = $this->DB->prepare("SELECT * FROM admin WHERE username=?");
                $query->bind_param("s", $username);
                $username = $this->DB->real_escape_string($_POST['username']);
                $query->execute();
                $result=$query->get_result();
                $array = [];
                if($result->num_rows == 1){
                    while($row = $result->fetch_assoc()){
                        array_push($array, $row);
                    }
                    array_push($array, ['msg'=>true]);
                    return $this->app->router->renderOnlyView("forgotPassword", json_encode($array));
                }else{
                    array_push($array, ['msg'=>false]);
                    return $this->app->router->renderOnlyView("forgotPassword", json_encode($array));
                }

                $query->close();
            }
        }
    }

    public function resetPassword(){
        if($this->app->request->getMethod() === "get"){
            if(isset($_GET['username']) && isset($_GET['verify'])){
                $username = $this->DB->real_escape_string($_GET['username']);
                $result = $this->DB->query("SELECT id FROM admin where username='$username'");
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $verify = md5($row['id']);
                    if($verify === $_GET['verify']){
                        return $this->app->router->renderOnlyView("resetPassword");
                    }
                }else{
                    
                }
            }
            $this->DB->close();
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['newpassword']) && isset($_POST['confirmpassword']) && isset($_POST['username'])){
                if(strcmp($_POST['newpassword'], $_POST['confirmpassword']) == 0){
                    $newPassword = $this->DB->real_escape_string($_POST['newpassword']);
                    $username = $this->DB->real_escape_string($_POST['username']);
                    $result = $this->DB->query("UPDATE admin SET password='$newPassword' WHERE username='$username'");
                    if($result == 1){
                        return header("Location: /login");
                    }
                }
            }
        }
    }

}