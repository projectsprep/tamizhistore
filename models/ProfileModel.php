<?php

namespace app\models;
use mysqli;
class ProfileModel{
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

    public function login($username, $pass){
        $query = "SELECT * FROM admin where username='$username'";
        $result = $this->conn->query($query);
        if($result->num_rows == 0){
            return header("Location: /login");
        }else{
            while($row = $result->fetch_assoc()){
                if($pass === $row['password']){
                    $this->setAccess($username);
                    return header("Location: /");
                }else{
                    return header("Location: /login");
                }
            }
        }
    }

    public function setAccess($username){
        $token = md5(rand(0, 100));
        $query = "Insert into login_access set username='$username', access_token='$token'";
        $result = $this->conn->query($query);
        if($result){
            return setcookie("user", $token, time() + 1000, "/");
        }else{
            return header("Location: /login");
        }
    }

    public function verifyToken($user){
        $query = "SELECT * FROM login_access where username='$user'";
        $result = $this->conn->query($query);

        // if($result > 0){
        //     while($row = $result->fetch_assoc()){
        //         if()
        //     }
        // }
    }

}