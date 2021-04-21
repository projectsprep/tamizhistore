<?php

namespace app\controllers;
use app\core\Controller;
use app\models\CountryCodeModel;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class CountryCodeController extends Controller{
    private $db;

    public function __construct(){
        $this->db = new CountryCodeModel();
    }
    public function read(){
        $json = $this->db->read("code");
        if($json){
            return $this->render("countryCode/codeList", $json);
        }else{
            return "Something went wrong";
        }
    }
}