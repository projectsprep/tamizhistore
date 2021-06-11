<?php

namespace App\models;

use app\models\DB;

class CartModel{
    private $conn = null;
    private $table = "cart";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->table = $this->conn->real_escape_string($this->table);
    }

    public function read($uid){
        $uid = $this->conn->real_escape_string($uid);

        $query = "SELECT * FROM $this->table INNER JOIN product on productid=product.id where userid=$uid";
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

    public function add($uid, $pid){
        $uid = $this->conn->real_escape_string($uid);
        $pid = $this->conn->real_escape_string($pid);
        $query = "INSERT INTO $this->table SET userid=$uid, productid=$pid";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM $this->table WHERE id=$id";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function increment($id){
        $id = $this->conn->real_escape_string($id);
        $query = "SELECT quantity FROM $this->table where id=$id";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $qty = $row['quantity'];
            $qty++;
            $query = "UPDATE $this->table SET quantity=$qty where id=$id";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function decrement($id){
        $id = $this->conn->real_escape_string($id);
        $query = "SELECT quantity FROM $this->table where id=$id";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $qty = $row['quantity'];
            if($qty == 1){
                return false;
            }
            $qty--;
            $query = "UPDATE $this->table SET quantity=$qty where id=$id";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}