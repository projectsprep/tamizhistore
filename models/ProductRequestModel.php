<?php

namespace app\models;

use app\models\DB;

class ProductRequestModel{
    private $conn = null;
    private $table = "productrequest";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read(){
        $query = "SELECT * FROM $this->table ORDER BY id DESC";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return false;
        }
    }
}