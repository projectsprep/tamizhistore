<?php

namespace app\controllers;

use app\core\Controller;
use app\models\PaymentModel;
use app\core\Application;
if(!(isset($_COOKIE['user'])))
header("Location: /login");

class PaymentController extends Controller{
    private $db;
    private $app;
    public function __construct(){
        $this->db = new PaymentModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("payment_list");
        if($json){
            return $this->render("payment/paymentList", $json);
        }else{
            return "Something went wrong";
        }
    }
}