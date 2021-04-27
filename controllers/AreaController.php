<?php

namespace app\controllers;

use app\core\Controller;
use app\models\AreaModel;
use app\core\Application;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class AreaController extends Controller{
    private $db;
    private $app;
    public function __construct(){
        $this->db = new AreaModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("area_db");
        if($json){
            return $this->render("area/areaList", $json);
        }else{
            return "Something went wrong";
        }
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("area/addArea");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
            }
        }
    }
}