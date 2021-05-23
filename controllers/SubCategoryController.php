<?php

namespace app\controllers;

use app\core\Controller;
use app\models\SubCategoryModel;
use app\core\Application;
use Exception;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}
if($_SESSION['notify'] === true){
    ?>
    <!-- <script>$("audio")[0].play();</script> -->
    <script>document.getElementsByTagName("audio")[0].play()</script>
    <?php
}
class SubCategoryController extends Controller{
    private $db;
    private $imageDest;
    private Application $app;
    public function __construct()
    {
        $this->db = new SubCategoryModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("subcategories/addSubcategory");
        }else if($this->app->request->getMethod() === "post"){
            try{
                if(isset($_POST['subcategoryname']) && ($_POST['subcategoryname'] !== "") && isset($_POST['category']) && ($_POST['category'] !== "")  && isset($_FILES['subcategoryimage']['name']) && $_FILES['subcategoryimage']['name'] != ""){
                    $validateImage = $this->validateImage();
                    if($validateImage === true){
                        if($this->db->create("subcategory", $_POST["subcategoryname"], $this->imageDest, $_POST['category'])){
                            $msg = urlencode("Added subcategory successfully!");
                            return header("Location: /subcategorylist?msg=$msg");    
                        }else{
                            throw new Exception("Unable to add new subcategory!");
                        }
                    }else{
                        throw new Exception($validateImage);
                    }
                }else{
                    throw new Exception("All input fields are required!");
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /subcategorylist/add?msg=$msg");
            }
        }
    }

    public function read(){
        $json = $this->db->read("subcategory");
        try{
            if($json){
                return $this->render("subcategories/subCategoryList", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /subcateorylist?msg=$msg");
        }
    }

    public function delete(){
        try{
            if(isset($_POST['id'])){
                if($this->db->delete("subcategory", $_POST['id'])){
                    return header("Location: /subcategorylist");
                }else{
                    $msg = urlencode("Unable to delete a category.");
                    return header("Location: /subcategorylist?msg=$msg");
                }
            }else{
                throw new Exception("Invalid Arguments!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /subcategorylist?msg=$msg");
        }
    }

    public function update(){
        $json = $this->db->read("category");
        try{
            if($this->app->request->getMethod() === "get"){
                if($json){
                    if(isset($_GET['id']) && $_GET['id']!=""){
                        $json = $this->db->edit("category", $_GET['id']);
                        return $this->render("categories/editCategory", $json);
                    }else{
                        $msg = urlencode("Invalid ID");
                        return header("Location: /categorylist?msg=$msg");
                        throw new Exception("Invalid Arguments!");
                    }
                }else{
                    throw new Exception("Unable to fetch data. Please try again later!");
                }
            }else if($this->app->request->getMethod() === "post"){
                if(isset($_POST['id']) && isset($_POST['subcategoryName']) && isset($_POST['category'])){
                    $validateImage = NULL;
                    if(isset($_FILES['subcategoryimage']['name']) && $_FILES['subcategoryimage']['name'] != ""){
                        $validateImage = $this->validateImage();
                    }
                    if($validateImage === true || $validateImage == NULL){
                        if($this->db->update("subcategory", $_POST['id'], $_POST['category'], $_POST['subcategoryName'], $validateImage == true ? $this->imageDest : "")){
                            $msg = urlencode("SubCategory updated successfully!");
                            return header("Location: /subcategorylist?msg=$msg");
                        }else{
                            throw new Exception("Unable to update SubCategory!");
                        }        
                    }else{
                        throw new Exception($validateImage);
                        // return $this->render("categories/temp", $json, json_encode(["msg"=>$validateImage]));
                    }
                }else{
                    throw new Exception("Unable to update SubCategory!");
                    // return $this->render("categories/categoryList", $json, json_encode(["msg"=>"Unable to add new category. Check if all values are set"]));
                }
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /subcategorylist?msg=$msg");
        }        
    }

    public function validateImage(){
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/category/";
        $targetFile = $targetDir . basename($_FILES['subcategoryimage']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if(isset($_POST['submit'])){
            $check = getimagesize($_FILES['subcategoryimage']['tmp_name']);
            if($check !== false){
                $uploadOk = 1;
            }else{
                $uploadOk = 0;
                return "File is not an image";
            }
        }

        //check if image file already exist
        if(file_exists($targetFile)){
            $uploadOk = 0;
            return "Image file already exist";
        }

        // limit the file size
        if($_FILES['subcategoryimage']['size'] > 5000000){
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
            if (move_uploaded_file($_FILES["subcategoryimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = "/assets/images/category/".basename($_FILES['subcategoryimage']['name']);
              return true;
            } else {
              return false;
            }
          }
    }
}