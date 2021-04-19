<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CategoryModel;
if(!(isset($_COOKIE['user'])))
header("Location: /login");

class CategoryController extends Controller{
    private $db;
    public function __construct()
    {
        $this->db = new CategoryModel();
    }

    public function add(){
        return $this->render("categories/addCategory");
    }

    public function upload(){
        if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
            $this->db->upload('category', $_POST['categoryName'], $_POST['categoryImage']);
        }
    }

    public function home(){
        return $this->render('home');
    }

    public function categoryList(){
        $json = $this->db->read("category");
        return $this->render("categories/categoryList", $json);
    }

    public function delete(){
        if(isset($_GET['id'])){
            $this->db->delete("category", $_GET['id']);
        }else{
            echo "Invalid ID";
        }
    }

    public function edit(){
        if(isset($_GET['id'])){
            $json = $this->db->edit("category", $_GET['id']);
            return $this->render("categories/editCategory", $json);
        }else{
            echo "Invalid ID";
        }
    }

    public function update(){
        if(isset($_POST['id']) and isset($_POST['categoryName']) and isset($_POST['categoryImage'])){
            $this->db->update("category", $_POST['id'], $_POST['categoryName'], $_POST['categoryImage']);
        }else{
            echo "Invalid ID";
        }
    }
}