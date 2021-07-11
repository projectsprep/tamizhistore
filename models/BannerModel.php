<?php

namespace app\models;

use app\models\DB;

class BannerModel{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function getBannerById($id){
        $query = "SELECT b.*, c.catname, s.name FROM banner b INNER JOIN category c on c.id = b.cid INNER JOIN subcategory s on s.id = b.sid WHERE b.id=$id";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return json_encode("No results found!");
        }
    }

    public function read(){
        $query = "SELECT * FROM banner order by id desc";
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

    public function update($id, $cid, $sid, $image){
        $id = $this->conn->real_escape_string($id);
        $cid = $this->conn->real_escape_string($cid);
        $sid = $this->conn->real_escape_string($sid);
        $image = $this->conn->real_escape_string($image);

        if($image == ""){
            $query = "UPDATE banner SET cid=$cid, sid=$sid WHERE id=$id";
        }else{
            $query = "UPDATE banner SET cid=$cid, sid=$sid, bimg='$image' WHERE id=$id";
        }

        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function create($cid, $sid, $image){
        $cid = $this->conn->real_escape_string($cid);
        $sid = $this->conn->real_escape_string($sid);
        $image = $this->conn->real_escape_string($image);

        $query = "INSERT INTO banner SET cid=$cid, sid=$sid, bimg='$image'";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM banner where id=$id";
        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }
}