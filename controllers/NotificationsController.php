<?php

namespace app\controllers;

use app\core\Controller;
use app\models\NotificationsModel;
use app\core\Application;
use Exception;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class NotificationsController extends Controller{
    private $db;
    private $app;

    public function __construct(){
        $this->db = new NotificationsModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("noti");
        try{
            if($json){
                return $this->render("notifications/notificationList", $json);
            }else{
                throw new Exception("No Notifications found");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /notifications?msg=$msg");
        }
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("notifications/addNotifications");
        }else if($this->app->request->getMethod() === "post"){
            try{
                if((isset($_POST['title']) && isset($_POST['message'])) || isset($_FILES['image'])){
                    if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                        return header("Location: /categorylist");
                    }else{
                        throw new Exception("Unable to create a new notification!");
                    }
                    // echo "<pre>";
                    // var_dump($_POST);
                    // var_dump($_FILES);
                    // echo "</pre>";
                }else{
                    throw new Exception("All input fields are required!");
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /notifications/add?msg=$msg");
            }
        }
    }

    public function delete(){
        try{
            if(isset($_POST['id'])){
                if($this->db->delete("noti", $_POST['id'])){
                    return true;
                }else{
                    return false;
                }
            }else{
                throw new Exception("Invalid Arguments!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /notifications?msg=$msg");
        }
    }

    public function push(){
        try{
            if(isset($_POST['id'])){
                if($this->db->push("noti", $_POST['id'])){
                    $msg = urlencode("Notification pushed!");
                    return header("Location: /notifications?msg=$msg");
                }else{
                    throw new Exception("Unable to push notification!");
                }
            }else{
                throw new Exception("Invalid Arguments!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /notifications?msg=$msg");
        }
    }

    public function validateImage(){
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/category/";
        $targetFile = $targetDir . basename($_FILES['categoryimage']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if(isset($_POST['submit'])){
            $check = getimagesize($_FILES['categoryimage']['tmp_name']);
            if($check !== false){
                $uploadOk = 1;
            }else{
                $uploadOk = 0;
                return "File is not an image";
            }
        }

        //check if image file already exist
        if(file_exists($targetFile)){
            return "Image file already exist";
            $uploadOk = 0;
        }

        // limit the file size
        if($_FILES['categoryimage']['size'] > 5000000){
            $uploadOk = 0;
            return "File size too large";
        }

        if($imageFileType != "jpg" && $imageFileType != 'png' && $imageFileType != "jpeg"){
            $uploadOk = 0;
            return "Only jpg, png and jpeg file formats are allowed";
        }

        if ($uploadOk == 0) {
            return "Your file didn't uploaded for some reasons";
          // if everything is ok, try to upload file
          } else {
            if (move_uploaded_file($_FILES["categoryimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = "/assets/images/category/".basename($_FILES['categoryimage']['name']);
              return true;
            } else {
              return false;
            }
          }
    }
}