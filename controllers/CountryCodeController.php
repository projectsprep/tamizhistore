<?php

namespace app\controllers;
use app\core\Controller;
use app\models\CountryCodeModel;
use app\core\Application;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

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
            if(isset($_POST['categoryName']) && isset($_POST['categoryImage'])){
                if($this->db->create('category', $_POST['categoryName'], $_POST['categoryImage'])){
                    return header("Location: /categorylist");
                }
            }
        }
    }
}