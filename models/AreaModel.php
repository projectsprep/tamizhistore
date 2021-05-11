<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class AreaModel{
    private $conn = null;

    public function __construct(){
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function create($table, $areaName, $delCharge, $status){
        $table = $this->conn->real_escape_string($table);
        $areaName = $this->conn->real_escape_string($areaName);
        $delCharge = $this->conn->real_escape_string($delCharge);
        $query = "INSERT INTO $table (`name`, dcharge, `status`) VALUES ('$areaName', '$delCharge', $status);";
        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function read($table){
        $query = "Select * from $table";
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

    public function getAreaById($table, $id){
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);
        $query = "SELECT * FROM $table where id=$id";
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

    public function update($table, $id, $areaName, $delCharge, $status){
        $table = $this->conn->real_escape_string($table);
        $areaName = $this->conn->real_escape_string($areaName);
        $delCharge = $this->conn->real_escape_string($delCharge);
        $status = $this->conn->real_escape_string($status);
        $id = htmlspecialchars($id);

        $query = "UPDATE $table set name='$areaName', dcharge='$delCharge' ,status='$status' where id=$id";
        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function edit($table, $id){
        $query = "SELECT * FROM $table where id=$id";
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

    public function delete($table, $id){
        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}