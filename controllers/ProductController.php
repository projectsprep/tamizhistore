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

    public function read(){
        $json = $this->db->read($this->table);
        return $this->render("products/productList", $json);
    }

    public function edit(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("products/editProduct");
        }else if($this->app->request->getMethod() === "post"){
            // if(isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["stock"]) && isset($_POST["publish"]) && isset($_POST["description"]) && isset($_POST["unit"]) && isset($_POST["price"]) && isset($_POST["discount"])){
            //     return $this->db->update();
            // }
        }
    }

    public function delete(){

    }

    public function add(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("products/addProduct");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["stock"]) && isset($_POST["publish"]) && isset($_POST["description"]) && isset($_POST["unit"]) && isset($_POST["price"]) && isset($_POST["discount"])){
                // return $this->db->add();
                echo "Yes cool";
            }else{
                echo "something bad happened";
            }
        }
    }
}