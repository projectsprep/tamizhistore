<?php

namespace app\models;

use app\models\DB;

class StepsInfoModel{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function getStepsInfoById($id){
        $id = $this->conn->real_escape_string($id);

        $query = "SELECT * FROM stepsinfo WHERE id=$id";
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

    public function read(){
        $query = "SELECT * FROM stepsinfo order by id desc";
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

    public function create($image, $msg){
        $msg = $this->conn->real_escape_string($msg);
        $image = $this->conn->real_escape_string($image);

        $query = "INSERT INTO stepsinfo SET message='$msg', image='$image'";
        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function update($image, $msg, $id){
        $image = $this->conn->real_escape_string($image);
        $msg = $this->conn->real_escape_string($msg);
        $id = $this->conn->real_escape_string($id);

        if($image == ""){
            $query = "UPDATE stepsinfo SET message='$msg' where id=$id";
        }else{
            $query = "UPDATE stepsinfo SET message='$msg', image='$image' where id=$id";
        }

        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM stepsinfo where id=$id";
        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }

    }
}