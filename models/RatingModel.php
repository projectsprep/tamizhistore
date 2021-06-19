<?php

namespace app\models;

use app\models\DB;

class RatingModel{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function productRating($uid, $pid, $rating){
        $uid = $this->conn->real_escape_string($uid);
        $pid = $this->conn->real_escape_string($pid);
        $rating = $this->conn->real_escape_string($rating);

        $query = "SELECT * FROM productrating where uid=$uid and pid=$pid";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $query = "UPDATE productrating SET rating=$rating WHERE uid=$uid and pid=$pid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            $query = "INSERT INTO productrating SET uid=$uid, pid=$pid, rating=$rating";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function userProductRating($uid, $pid){
        $pid = $this->conn->real_escape_string($pid);
        $uid = $this->conn->real_escape_string($uid);

        $query = "SELECT rating FROM productrating where uid=$uid and pid=$pid";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row['rating'];
        }else{
            return false;
        }
    }

    public function getProductRating($pid){
        $pid = $this->conn->real_escape_string($pid);
        $query = "SELECT rating FROM productrating where pid=$pid";
        $result = $this->conn->query($query);
        $sum = 0;
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $sum += $row['rating'];
            }
            $result = $sum/$result->num_rows;
            return $result;
        }else{
            return false;
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}