<?php

namespace App\models;

use app\models\DB;

class CountryCodeModel{
    
    private $conn = null;

    public function __construct(){
            $this->conn = new DB();
            $this->conn = $this->conn->conn();
    }

    public function create($table, $ccode, $status){
        $query = "INSERT INTO $table (ccode, status) VALUES ('$ccode', '$status');";
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

    public function getCodeById($table, $id){
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

    public function update($table, $id, $ccode, $status){
        $table = $this->conn->real_escape_string($table);
        $ccode = $this->conn->real_escape_string($ccode);
        $status = $this->conn->real_escape_string($status);
        $id = htmlspecialchars($id);

        $query = "UPDATE $table set ccode='$ccode', `status`='$status' where id=$id";
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
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);
        
        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}