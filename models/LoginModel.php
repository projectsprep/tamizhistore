<?php

namespace App\models;

use app\models\DB;

class LoginModel{
    private $conn = null;
    private $table = 'users';
    private $rider = "rider";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function login($username, $password){
        $username = $this->conn->real_escape_string($username);
        $password = $this->conn->real_escape_string($password);

        $query = "SELECT * FROM $this->table WHERE username='$username'";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $pass = password_verify($password, $row['password']);
            if(password_verify($password, $row['password'])){
                return array("id"=>$row['id'], "name"=>$row['name'], "username"=>$row['username']);
            }else{
                return "Invalid password!";
            }
        }else{
            return "Username not found!";
        }
    }

    public function create($username, $password, $name){
        $username = $this->conn->real_escape_string($username);
        $password = $this->conn->real_escape_string($password);
        $name = $this->conn->real_escape_string($name);

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "SELECT username FROM $this->table WHERE username='$username'";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            return "Username does not exist! Please try another username!";
        }else{
            $query = "INSERT INTO $this->table SET username='$username', `password`='$password', `name`='$name'";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
            $query = "SELECT * FROM $this->table where username='$username'";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                    return array("id"=>$row['id'], "name"=>$row['name'], "username"=>$row['username']);
                }
            }else{
                return "Unable to create new account!";
            }
        }

    }

    public function update($id, $username, $name, $password){
        $id = $this->conn->real_escape_string($id);
        $username = $this->conn->real_escape_string($username);
        $name = $this->conn->real_escape_string($name);
        $password = $this->conn->real_escape_string($password);

        $query = "UPDATE $this->table SET username='$username', name='$name', password='$password' where id=$id";
        $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function riderLogin($username, $password){
        $username = $this->conn->real_escape_string($username);
        $password = $this->conn->real_escape_string($password);

        $query = "SELECT * FROM $this->rider WHERE username='$username'";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $pass = password_verify($password, $row['password']);
            if(password_verify($password, $row['password'])){
                return array("id"=>$row['id'], "name"=>$row['name'], "username"=>$row['username']);
            }else{
                return "Invalid password!";
            }
        }else{
            return "Username not found!";
        }
    }

}