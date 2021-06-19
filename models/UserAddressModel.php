<?php

namespace App\models;

use app\models\DB;

class UserAddressModel{
    private $conn = null;
    private $table = "useraddress";

    public function __construct(){
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->table = $this->conn->real_escape_string($this->table);
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function read($uid){
        $uid = $this->conn->real_escape_string($uid);
        $query = "SELECT * FROM $this->table where userid=$uid";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return $array;
        }else{
            return false;
        }
    }

    public function add($uid, $address){
        $uid = $this->conn->real_escape_string($uid);
        $address = $this->conn->real_escape_string($address);

        $query = "INSERT INTO $this->table SET userid=$uid, address='$address'";
        $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function update($id, $uid, $address){
        $id = $this->conn->real_escape_string($id);
        $uid = $this->conn->real_escape_string($uid);
        $address = $this->conn->real_escape_string($address);

        $query = "UPDATE $this->table SET userid=$uid, address='$address' where id=$id";
        $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM $this->table WHERE id=$id";
        $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }
}