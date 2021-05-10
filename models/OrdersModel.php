<?php

namespace app\models;
use app\models\DB;

class OrdersModel{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }
    public function read($table){
        $table = $this->conn->real_escape_string($table);
        $query = "SELECT * FROM $table";
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