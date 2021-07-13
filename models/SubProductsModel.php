<?php

namespace app\models;

use app\models\DB;

class SubProductsModel{
    private $conn = null;
    private $table = "subproduct";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function readById($id){
        $id = $this->conn->real_escape_string($id);

        $query = "SELECT * FROM $this->table WHERE id = $id";
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

    public function read(){
        $query = "SELECT * FROM $this->table order by ID desc;";
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

    public function update($id, $pid, $price, $unit){
        $id = $this->conn->real_escape_string($id);
        $pid = $this->conn->real_escape_string($pid);
        $price = $this->conn->real_escape_string($price);
        $unit = $this->conn->real_escape_string($unit);

        $query = "UPDATE $this->table SET pid=$pid, unit='$unit', price=$price WHERE id=$id";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM $this->table WHERE id=$id;";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function add($pid, $price, $unit){
        $pid = $this->conn->real_escape_string($pid);
        $price = $this->conn->real_escape_string($price);
        $unit = $this->conn->real_escape_string($unit);

        $query = "INSERT INTO $this->table SET pid=$pid, price=$price, unit='$unit'";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

}