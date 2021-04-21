<?php

namespace app\controllers;

use app\core\Controller;
use app\models\NotificationsModel;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class NotificationsController extends Controller{
    private $db;

    public function __construct(){
        $this->db = new NotificationsModel();
    }
    public function read(){
        $json = $this->db->read("noti");
        if($json){
            return $this->render("notifications/notificationList", $json);
        }else{
            return "Something went wrong";
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