<?php

namespace app\models;

use app\models\DB;


class BookingsModel{
    private $conn = null;
    private $table = "bookings";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function getAllBookings(){
        $query = "SELECT * FROM $this->table ORDER BY id DESC";
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

    public function create($uid, $pid, $phone, $address, $msg, $note){
        $uid = $this->conn->real_escape_string($uid);
        $pid = $this->conn->real_escape_string($pid);
        $phone = $this->conn->real_escape_string($phone);
        $address = $this->conn->real_escape_string($address);
        $msg = $this->conn->real_escape_string($msg);
        $note = $this->conn->real_escape_string($note);

        $query = "SELECT * FROM $this->table WHERE userid=$uid and productid=$pid and bookingstatus='pending'";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            return "Booking already exist!";
        }else{
            $query = "INSERT INTO $this->table SET userid=$uid, productid=$pid, customerphone=$phone, customeraddress='$address', message='$msg', note='$note'";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function cancel($uid, $id){
        $uid = $this->conn->real_escape_string($uid);
        $id = $this->conn->real_escape_string($id);

        $query = "UPDATE $this->table SET bookingstatus='cancelled' WHERE userid=$uid AND id=$id";
        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function read($uid){
        $uid = $this->conn->real_escape_string($uid);

        $query = "SELECT * FROM $this->table WHERE userid=$uid";
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
}