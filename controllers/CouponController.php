<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CouponModel;
use app\core\Application;
use Exception;

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
            try{
                if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                    if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                        $msg = urlencode("Created new coupon successfully!");
                        return header("Location: /couponlist?msg=$msg");
                    }else{
                        throw new Exception("All input fields are required!");
                    }
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /couponlist?msg=$msg");
            }
        }
    }

    public function read(){
        $json = $this->db->read("tbl_coupon");
        try{
            if($json){
                return $this->render("coupon/couponList", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /couponlist?msg=$msg");
        }
    }

    public function delete(){
        try{
            if(isset($_GET['id'])){
                if($this->db->delete("category", $_GET['id'])){
                    $msg = urlencode("Coupon deleted successfully!");
                    return header("Location: /couponlist?msg=$msg");
                }else{
                    $msg = urlencode("Unable to delete coupon!");
                    return header("Location: /couponlist?msg=$msg");
                }
            }else{
                throw new Exception("Invalid ID");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /couponlist?msg=$msg");
        }
    }

    public function edit(){
        if($this->app->request->getMethod() === "get"){
            try{
                if(isset($_GET['id'])){
                    $json = $this->db->edit("category", $_GET['id']);
                    return $this->render("categories/editCategory", $json);
                }else{
                    throw new Exception("Invalid ID");
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /couponlist?msg=$msg");
            }
        }else if($this->app->request->getMethod() === "post"){
            try{
                if(isset($_POST['id']) && isset($_POST['expiryDate']) && isset($_POST['couponCode']) && isset($_POST['couponTitle']) && isset($_POST['couponStatus']) && isset($_POST['minAmt']) && isset($_POST['discount']) && isset($_POST['description'])){
                    if($this->db->update("tbl_coupon", $_POST['id'], $_POST['expiryDate'], $_POST['couponCode'], $_POST['couponTitle'], $_POST['couponStatus'], $_POST['minAmt'], $_POST['discount'], $_POST['description'])){
                        $msg = urlencode("Coupon updated successfully!");
                        return header("Location: /couponlist?msg=$msg");
                    }else{
                        $msg = urlencode("Unable to update coupon!");
                        return header("Location: /couponlist?msg=$msg");
                    }
                }else{
                    throw new Exception("Invalid ID");
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /couponlist?msg=$msg");
            }
        }
        
    }
}