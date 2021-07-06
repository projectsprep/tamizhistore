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

    public function __destruct()
    {
        $this->conn->close();
    }

    public function read($uid){
        $uid = $this->conn->real_escape_string($uid);

        $query = "SELECT c.id id, c.userid, c.productid, c.quantity, p.pname, p.sname, p.cid, p.sid, p.psdesc, p.pgms, p.pprice, p.status, p.stock, p.pimg, p.prel, p.date, p.discount, p.popular FROM $this->table c INNER JOIN product p on c.productid=p.id where userid=$uid";
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
        $query = "SELECT * FROM $this->table where userid=$uid and productid=$pid";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            return "Cart already exists!";
        }
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