<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class CategoryModel{    
    private $conn = null;

    public function __construct(){
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function create($table, $categoryName, $categoryImage){
        $query = "INSERT INTO $table (catname, catimg) VALUES ('$categoryName', '$categoryImage');";
        $result = $this->conn->query($query);
        if($result){
            
            
            return true;
        }else{
            
            return false;
        }
    }

    public function getCount(){
        $tables = ['category', "subcategory", "product", "tbl_coupon", "area_db", "timeslot", "user", "feedback", "code", "rider", "noti", "rate_order", "orders"];
        $array = [];
        foreach($tables as $table){
            $query = "SELECT COUNT(id) $table FROM $table";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array, $row);
                }
            }
        }

        $status = ['pending', 'cancelled'];
        foreach($status as $stat){
            $query = "SELECT COUNT(id) $stat FROM orders where status='$stat'";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array, $row);
                }
            }
        }        
        
        return json_encode($array);
    }

    public function read($table){
        $query = "Select * from $table ORDER BY id DESC";
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

    public function getCategoryById($table, $id){
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);
        $query = "SELECT * FROM $table WHERE id = $id";
        $result = $this->conn->query($query);
        $array=[];
       
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return json_encode("No results found");
        }

    }

    public function update($table, $id, $catname, $catimage){
        $query = "UPDATE $table set catname='$catname', catimg='$catimage' where id=$id";
        $result = $this->conn->query($query);
        if($result){
            
            
            return true;
        }else{
            
            return false;
        }
    }

    public function edit($table, $id){
        $query = "SELECT * FROM $table where id=$id";
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

    public function delete($table, $id){
        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if($result == 1){
            
            return true;
        }else{
            
            return false;
        }
    }
}