<?php

namespace app\controllers;

use app\core\Controller;
use app\models\TamizhiStoreApp;

class CategoryController extends Controller{
    private $db;
    public function __construct()
    {
        $this->db = new TamizhiStoreApp();
    }
    public function home(){
        return $this->render('home');
    }

    public function categoryList(){
        $json = $this->db->read("category");
        return $this->render("categoryList", $json);
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
            return $this->render("editCategory", $json);
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