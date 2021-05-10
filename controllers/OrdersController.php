<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\OrdersModel;
use Exception;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class OrdersController extends Controller{
    private $db;
    
    public function __construct(){
        $this->db = new OrdersModel();
    }
    public function read(){
        $json = $this->db->read("orders");
        try{
            if($json){
                return $this->render("orders/ordersList", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /orders?msg=$msg");
        }
    }
}