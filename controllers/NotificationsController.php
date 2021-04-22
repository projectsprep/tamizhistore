<?php

namespace app\controllers;

use app\core\Controller;
use app\models\NotificationsModel;
use app\core\Application;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class NotificationsController extends Controller{
    private $db;
    private $app;

    public function __construct(){
        $this->db = new NotificationsModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("noti");
        if($json){
            return $this->render("notifications/notificationList", $json);
        }else{
            return "Something went wrong";
        }
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("notifications/addNotifications");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
            }
        }
    }

    public function delete(){
        if(isset($_GET['id'])){
            if($this->db->delete("noti", $_GET['id'])){
                return header("Location: /notifications");
            }else{
                return "Cannot be deleted";
            }
        }
    }

    public function push(){
        if(isset($_GET['id'])){
            if($this->db->push("noti", $_GET['id'])){
                return "Notification Pushed";
            }else{
                "Notification not pushed";
            }
        }
    }
}