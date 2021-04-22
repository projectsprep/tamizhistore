<?php

namespace app\controllers;
use app\core\Controller;
use app\models\ProductsModel;
use app\core\Application;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class ProductController extends Controller{
    private $db;
    private $table = "product";
    private Application $app;
    
    public function __construct()
    {
        $this->db = new ProductsModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("products/addProduct");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["outofstock"]) && isset($_POST["publish"]) && isset($_POST["popular"]) && isset($_POST["description"]) && isset($_POST["unit"]) && isset($_POST["price"]) && isset($_POST["discount"])){
                if($this->db->create("product", $_POST["productName"], $_POST["sellerName"], $_POST["category"], $_POST["subCategory"], $_POST["outofstock"], $_POST["publish"], $_POST["description"], $_POST["unit"], $_POST["price"], $_POST["discount"], $_POST['popular'])){
                    return header("Location: /productlist");
                }else{
                    return header("Location: /productlist/add");
                }
            }
        }
    }

    public function read(){
        // $json = $this->db->read($this->table);
        // if($json){
            return $this->render("products/productList");
        // }else{
        //     return false;
        // }
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
            // if(isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["stock"]) && isset($_POST["publish"]) && isset($_POST["description"]) && isset($_POST["unit"]) && isset($_POST["price"]) && isset($_POST["discount"])){
            //     return $this->db->update();
            // }
        }
    }

    public function delete(){
        if(isset($_GET['id'])){
            if($this->db->delete("product", $_GET['id'])){
                return header("Location: /productlist");
            }else{
                return "content cannot be deleted";
            }
        }else{
            return "why not";
        }
    }

}