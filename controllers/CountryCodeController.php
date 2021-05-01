<?php

namespace app\controllers;
use app\core\Controller;
use app\models\CountryCodeModel;
use app\core\Application;
session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class CountryCodeController extends Controller{
    private $db;
    private $app;

    public function __construct(){
        $this->db = new CountryCodeModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("code");
        if($json){
            return $this->render("countryCode/codeList", $json);
        }else{
            return "Something went wrong";
        }
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("countrycode/addCode");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['cc']) && isset($_POST['codeStatus'])){
                $status = $_POST['status'] == "publish" ? 1 : 0;
                if($this->db->create('code', $_POST['ccode'], $status)){
                    return $this->render("countrycode/codeList");
                }else{
                    return $this->render("countrycode/codeList");
                }
            }
        }
    }
}