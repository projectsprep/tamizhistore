<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CategoryModel;
use app\core\Application;
session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class CategoryController extends Controller{
    private $db;
    private $imageDest;
    private Application $app;
    public function __construct()
    {
        $this->db = new CategoryModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function add(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("categories/addCategory");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['categoryName'])){
                if($this->validateImage() == true){
                    if($this->db->create("category", $_POST["categoryName"], $this->imageDest)){
                        return header("Location: /categorylist");
                    }else{
                        return header("Location: /categorylist/add");
                    }
                }else{
                    echo "something happened";
                }
            }else{
                echo "here somethng happened";
            }
        }
    }

    public function home(){
        $json = $this->db->getCount();
        return $this->render('home', $json);
    }

    public function read(){
        $json = $this->db->read("category");
        return $this->render("categories/categoryList", $json);
    }

    public function delete(){
        if(isset($_GET['id'])){
            if($this->db->delete("category", $_GET['id'])){
                return header("Location: /categorylist");
            }
        }else{
            echo "Invalid ID";
        }
    }

    public function update(){
        if($this->app->request->getMethod() === "get"){
            if(isset($_GET['id'])){
                $json = $this->db->edit("category", $_GET['id']);
                return $this->render("categories/editCategory", $json);
            }else{
                echo "Invalid ID";
            }
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['id']) and isset($_POST['categoryName']) and isset($_POST['categoryimage'])){
                if($this->db->update("category", $_POST['id'], $_POST['categoryName'], $_POST['categoryimage'])){
                    return header("Location: /categorylist");
                }
            }else{
                echo "Invalid ID";
            }
        }
        
    }

    public function validateImage(){
        echo "<pre>";
        var_dump($_FILES);
        echo "</pre>";
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
                echo "File is not an image";
            }
        }

        //check if image file already exist
        if(file_exists($targetFile)){
            echo "Image file already exist";
            $uploadOk = 0;
        }

        // limit the file size
        if($_FILES['categoryimage']['size'] > 5000000){
            $uploadOk = 0;
            echo "File size too large";
        }

        if($imageFileType != "jpg" && $imageFileType != 'png' && $imageFileType != "jpeg"){
            $uploadOk = 0;
            echo "Only jpg, png and jpeg file formats are allowed";
        }

        if ($uploadOk == 0) {
            echo "Your file didn't uploaded for some reasons";
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