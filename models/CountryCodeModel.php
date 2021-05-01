<?php

namespace App\models;

use mysqli;
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

    public function update($table, $id, $catname, $catimage){
        $query = "UPDATE $table set catname='$catname', catimg='$catimage' where id=$id";
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