<?php

namespace app\models;

use app\models\DB;

class CustomersModel
{
    private $table = 'user';
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read()
    {
        $this->table = $this->conn->real_escape_string($this->table);

        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return json_encode($array);
        } else {
            return false;
        }
    }

    public function readFeedback()
    {
        $query = "SELECT f.*, u.username user FROM feedback f INNER join users u on u.id = f.uid";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return json_encode($array);
        } else {
            return false;
        }
    }

    public function updateFeedback($msg, $uid){
        $query = "SELECT * FROM feedback WHERE msg='$msg' and uid=$uid";

        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $query = "UPDATE feedback SET msg='$msg' WHERE uid=$uid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            $query = "INSERT INTO feedback SET msg='$msg', uid=$uid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }
    }
}
