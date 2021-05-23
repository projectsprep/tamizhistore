<?php

namespace app\controllers;
use app\core\Controller;
use app\models\CustomersModel;
use Exception;

class CustomersController extends Controller{
    private $db;
    private $app;
    public function __construct()
    {
        $this->db = new CustomersModel();
    }

    public function readCustomers(){
        $json = $this->db->read();
        try{
            if($json){
                return $this->render("customers/customersList", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /customers?msg=$msg");
        }

    }

    public function readFeedback(){
        $json = $this->db->readFeedback();
        try{
            if($json){
                return $this->render("customers/customerFeedback", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /customers/rating?msg=$msg");
        }
    }
}