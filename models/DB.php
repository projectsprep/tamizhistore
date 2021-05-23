<?php

namespace app\models;

use Exception;
use mysqli;

class DB{

    protected string $hostName = "cloud1.zolahost.net";
    protected string $port = "3306";
    protected string $dbName = "testtamizhistore";
    protected string $username = "projectsprep";
    protected string $password = "Prep5@#$";

    // protected string $hostName = "cloud1.zolahost.net";
    // protected string $port = "3306";
    // protected string $dbName = "tamizhistoreapp";
    // protected string $username = "tamizhistoreadmin";
    // protected string $password = "Tamizhistore2020";

    // protected string $hostName = "localhost";
    // protected string $port = "3306";
    // protected string $dbName = "tamizhistoreapp";
    // protected string $username = "root";
    // protected string $password = "dharshan";

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

        // try{
        //     $this->conn = new mysqli($this->hostName, $this->username, $this->password, $this->dbName);
        //     if($this->conn->connect_error){
        //         throw new Exception("connection error".$this->conn->connect_error);
        //     }else{
        //         return $this->conn;
        //     }
        // }catch(Exception $exception){
        //     die($exception->getMessage());
        // }
    }
}