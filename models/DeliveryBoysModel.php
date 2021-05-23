<?php

namespace app\models;

use app\models\DB;

class DeliveryBoysModel{
    private $conn;
    private $table = "rider";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }
    public function read(){
        $query = "Select * from $this->table";
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

    public function delete($id){
        $query = "DELETE FROM $this->table where id=$id";
        $result = $this->conn->query($query);
        if($result == 1){
            return true;
        }else{
            
            return false;
        }
    }
    
    public function update($id, $status){
        $query = "UPDATE $this->table SET status=$status where id=$id";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function create($name, $mobile, $email, $address, $password){
        $query = "INSERT INTO $this->table SET name='$name', mobile='$mobile', email='$email', address='$address', password='$password'";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }
}