<?php

namespace app\controllers;

use app\core\Controller;
use app\models\AreaModel;
if(!(isset($_COOKIE['user'])))
header("Location: /login");

class AreaController extends Controller{
    private $db;

    public function __construct(){
        $this->db = new AreaModel();
    }
    public function read(){
        $json = $this->db->read("area_db");
        if($json){
            return $this->render("area/areaList", $json);
        }else{
            return "Something went wrong";
        }
    }
}