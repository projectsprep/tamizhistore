<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CouponModel;
use app\core\Application;
session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class CouponController extends Controller{
    private $db;
    private Application $app;
    public function __construct()
    {
        $this->db = new CouponModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("coupon/addCoupon");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
            }
        }
    }

    public function read(){
        $json = $this->db->read("tbl_coupon");
        return $this->render("coupon/couponList", $json);
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