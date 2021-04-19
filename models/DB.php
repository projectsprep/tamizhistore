<?php

namespace app\models;
use mysqli;

class DB{
    protected string $hostName = "localhost";
    protected string $port = "3306";
    protected string $dbName = "tamizhistoreapp";
    protected string $username = "root";
    protected string $password = "dharshan";

    private $conn = null;

    public function conn(){
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
}