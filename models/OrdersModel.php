<?php

namespace app\models;
use app\models\DB;

class OrdersModel{
    private $conn = null;
    private $table = "orders";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }
    public function read(){
        $table = $this->conn->real_escape_string($this->table);
        $query = "SELECT o.*, r.name rider, a.area `address` FROM $this->table o LEFT join rider r on o.rid=r.id LEFT JOIN address a on o.address_id=a.id";
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
    
    public function readPending(){
        $table = $this->conn->real_escape_string($this->table);
        $query = "SELECT o.*, r.name rider, a.area `address` FROM $table o LEFT join rider r on o.rid=r.id LEFT JOIN address a on o.address_id=a.id where o.status = 'pending'";
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