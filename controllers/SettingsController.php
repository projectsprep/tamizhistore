<?php

namespace app\controllers;

use app\core\Controller;
use app\models\DB;

class SettingsController extends Controller{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read(){
        $query = "SELECT * FROM settings";
        $result = $this->conn->query($query);
        $array = [];

        $query = "SELECT * FROM setting";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode(array("result"=>true, "data"=>$array));
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No settings found"));
        }
    }
}