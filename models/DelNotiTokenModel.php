<?php

namespace app\models;

use app\models\DB;

class DelNotiTokenModel{
    private $conn = null;
    private $table = "rnotitoken";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function addToken($rid, $token){
        $rid = $this->conn->real_escape_string($rid);
        $token = $this->conn->real_escape_string($token);

        $query = "SELECT * FROM $this->table WHERE rid=$rid";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $query = "UPDATE $this->table SET token='$token' WHERE rid=$rid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            $query = "INSERT INTO $this->table SET token='$token', rid=$rid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function logout($rid){
        $query = "DELETE FROM $this->table WHERE rid=$rid";
        $this->conn->query($query);

        return true;
    }
}