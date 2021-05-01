<?php

namespace app\controllers;
use app\core\Controller;
use app\models\ProductsModel;
use app\core\Application;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class ProductController extends Controller{
    private $db;
    private $table = "product";
    private Application $app;
    private $imageDest;
    
    public function __construct()
    {
        $this->db = new ProductsModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("products/addProduct");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["outofstock"]) && isset($_POST["publish"]) && isset($_POST["popular"]) && isset($_POST["description"]) && isset($_POST["range"]) && isset($_POST["price"]) && isset($_POST["discount"]) && isset($_FILES['productimage'])){
                
                if($this->validateImage() == true){
                    if($this->db->create("product", $_POST["productName"], $this->imageDest, $_POST["sellerName"], $_POST["category"], $_POST["subCategory"], $_POST["outofstock"], $_POST["publish"], $_POST["description"], $_POST["unit"], $_POST["price"], $_POST["discount"], $_POST['popular'])){
                        $msg = urlencode("New product created successfully");
                        return header("Location: /productlist?msg=$msg");
                    }else{
                        return header("Location: /productlist/add");
                    }
                }else{
                    echo "something happened";
                }
            }else{
                echo "<pre>";
                var_dump($_POST);
                echo "</pre>";
            }
        }
    }

    public function read(){
        $json = $this->db->read($this->table);
        if($json){
            return $this->render("products/productList", $json);
        }else{
            return false;
        }
    }

    public function update(){
        if($this->app->request->getMethod() === "get"){
            if(isset($_GET['id'])){
                $json = $this->db->edit("product", $_GET['id']);
                if($json){
                    return $this->render("products/editProduct", $json);
                }else{
                    return "Cannot be updated";
                }
            }
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST["id"]) && isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["outofstock"]) && isset($_POST["publish"]) && isset($_POST["popular"]) && isset($_POST["description"]) && isset($_POST["range"]) && isset($_POST["price"]) && isset($_POST["discount"])){
                if($this->db->update("product", $_POST['id'],$_POST["productName"], $_POST["sellerName"], $_POST["category"], $_POST["subCategory"], $_POST["outofstock"], $_POST["publish"], $_POST["description"], $_POST["range"], $_POST["price"], $_POST["discount"], $_POST['popular'])){
                    $msg = urlencode("Product updated!");
                    return header("Location: /productlist?msg=$msg");
                }else{
                    echo "<pre>";
                    var_dump($_POST);
                    echo "</pre>";
                }
            }else{
                echo "<pre>";
                var_dump($_POST);
                echo "</pre>";
            }
        }
    }

    public function delete(){
        if(isset($_POST['id'])){
            if($this->db->delete("product", $_POST['id'])){
                return header("Location: /productlist?something=something");
            }else{
                return "content cannot be deleted";
            }
        }else{
            return "why not";
        }
    }

    public function validateImage(){
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/product/";
        $targetFile = $targetDir . basename($_FILES['productimage']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if(isset($_POST['submit'])){
            $check = getimagesize($_FILES['productimage']['tmp_name']);
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
        if($_FILES['productimage']['size'] > 5000000){
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
            if (move_uploaded_file($_FILES["productimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = "/assets/images/product/".basename($_FILES['productimage']['name']);
              return true;
            } else {
              return false;
            }
          }
    }

}