<?php

namespace App\models;

use mysqli;

class TamizhiStoreApp{
    // protected string $hostName = "cloud1.zolahost.net";
    // protected string $port = "3306";
    // protected string $dbName = "tamizhistoreapp";
    // protected string $username = "tamizhistoreadmin";
    // protected string $password = "Tamizhistore2020";
    protected string $hostName = "localhost";
    protected string $port = "3306";
    protected string $dbName = "tamizhistoreapp";
    protected string $username = "root";
    protected string $password = "dharshan";
    private $conn = null;

    public function __construct(){
        if($this->conn == null){
            $this->conn = new mysqli($this->hostName, $this->username, $this->password, $this->dbName);
            if($this->conn->connect_error){
                die("Error connecting to Database");
            }else{
                return $this->conn;
            }
        }else{
            return $this->conn;
        }
    }

    public function read($table){
        $query = "Select * from $table";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            return "No results found";
        }
    }

    public function delete($table, $id){
        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if($result){
            header("Location: /categorylist");
        }else{
            echo "Record cannot be deleted";
        }
    }

    public function update($table, $id, $catname, $catimage){
        $query = "UPDATE $table set catname='$catname', catimg='$catimage' where id=$id";
        $result = $this->conn->query($query);
        if($result){
            header("Location: /categorylist");
        }else{
            echo "Record cannot be deleted";
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
            return "No results found";
        }
    }
}