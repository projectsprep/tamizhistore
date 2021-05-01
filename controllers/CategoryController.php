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
        $json = $this->db->read("category");
        if($this->app->request->getMethod() === "get"){
            return $this->render("categories/tempAC");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['categoryName'])){
                $validateImage = $this->validateImage();
                if($validateImage === true){
                    if($this->db->create("category", $_POST["categoryName"], $this->imageDest)){
                        return header("Refresh: 1; URL=/categorylist");
                    }else{
                        $msg=urlencode("Unable to add new category");
                        return header("Location: /categorylist?msg=".$msg);    
                        // return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Unable to add new category"]));
                    }
                }else{
                    $msg=urlencode($validateImage);
                    return header("Location: /categorylist?msg=".$msg);
                    // return $this->render("categories/tempCL", $msg=json_encode(["msg"=>$validateImage]));
                }
            }else{
                $msg=urlencode("Unable to add new category");
                return header("Location: /categorylist?msg=".$msg);

                // return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Unable to add new category. Check if all values are set"]));
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
        // return $this->render("categories/categoryList");
    }

    public function delete(){
        $json = $this->db->read("category");
        if(isset($_POST['id'])){
            if($this->db->delete("category", $_POST['id'])){
                return header("Location: /categorylist");
            }else{
                return "something";
            }
        }else{
            return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Unable to delete a category"]));
        }
    }

    public function update(){
        $json = $this->db->read("category");
        if($this->app->request->getMethod() === "get"){
            if(isset($_GET['id']) && $_GET['id']!=""){
                $json = $this->db->edit("category", $_GET['id']);
                return $this->render("categories/editCategory", $json);
            }else{
                return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Invalid Id"]));
            }
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['id']) && isset($_POST['categoryName']) && isset($_FILES['categoryimage'])){
                $validateImage = $this->validateImage();
                if($validateImage === true){
                    if($this->db->update("category", $_POST['id'],$_POST["categoryName"], $this->imageDest)){
                        return header("Refresh: 1; URL=/categorylist");
                    }else{
                        $msg = urlencode("Unable to update category");
                        return header("Location: /categorylist?msg=$msg");
                        // return $this->render("categories/tempCL", $json, json_encode(["msg"=>"Unable to add new category"]));
                    }
                }else{
                    $msg = urlencode("$validateImage");
                    return header("Location: /categorylist?msg=$msg");

                    // return $this->render("categories/temp", $json, json_encode(["msg"=>$validateImage]));
                }
            }else{
                $msg = urlencode("Unable to update category");
                return header("Location: /categorylist?msg=$msg");

                // return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Unable to add new category. Check if all values are set"]));
            }
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