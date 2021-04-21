<?php

namespace app\controllers;

use app\core\Controller;
use app\models\TimeSlotsModel;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class TimeSlotsController extends Controller{
    private $db;

    public function __construct(){
        $this->db = new TimeSlotsModel();
    }
    public function read(){
        $json = $this->db->read("timeslot");
        if($json){
            return $this->render("timeslots/tslists", $json);
        }else{
            return "Something went wrong";
        }
    }
}