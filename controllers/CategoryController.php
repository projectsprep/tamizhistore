<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CategoryModel;
use app\core\Application;
if(!(isset($_COOKIE['user'])))
header("Location: /login");

class CategoryController extends Controller{
    private $db;
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
            if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
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
            if(isset($_POST['id']) and isset($_POST['categoryName']) and isset($_POST['categoryImage'])){
                if($this->db->update("category", $_POST['id'], $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
            }else{
                echo "Invalid ID";
            }
        }
        
    }
}