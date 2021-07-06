<?php

namespace app\models;

use app\models\DB;

class NotiTokenModel{
    private $conn = null;
    private $table = "notitoken";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function logout($uid){
        $query = "DELETE FROM $this->table WHERE uid=$uid";
        $result = $this->conn->query($query);

        return true;
    }

    public function add(int $uid, string $token){
        $uid = $this->conn->real_escape_string($uid);
        $token = $this->conn->real_escape_string($token);

        $query = "SELECT * FROM $this->table where uid=$uid";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            $query = "UPDATE $this->table SET token='$token' WHERE uid=$uid";
            $this->conn->query($query);

            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            $query = "INSERT INTO $this->table SET token='$token', uid=$uid";
            $result = $this->conn->query($query);

            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }
    }
}