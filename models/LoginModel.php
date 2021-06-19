<?php

namespace App\models;

use app\models\DB;

class LoginModel{
    private $conn = null;
    private $table = 'users';

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function login($username, $phone, $password){
        $username = $this->conn->real_escape_string($username);
        $phone = $this->conn->real_escape_string($phone);
        $password = $this->conn->real_escape_string($password);

        $query = "SELECT * FROM $this->table WHERE username='$username' and phone=$phone";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $pass = password_verify($password, $row['password']);
            if(password_verify($password, $row['password'])){
                return array("id"=>$row['id'], "name"=>$row['name'], "username"=>$row['username'], "phone"=>$row['phone']);
            }else{
                return "Invalid password!";
            }
        }else{
            return "Username not found!";
        }
    }

    public function create($username, $password, $phone, $name){
        $username = $this->conn->real_escape_string($username);
        $phone = $this->conn->real_escape_string($phone);
        $password = $this->conn->real_escape_string($password);
        $name = $this->conn->real_escape_string($name);

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->table SET username='$username', `password`='$password', `name`='$name', phone=$phone";
        $result = $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            $query = "SELECT * FROM $this->table where username='$username'";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return array("id"=>$row['id'], "name"=>$row['name'], "username"=>$row['username'], "phone"=>$row['phone']);
            }
        }else{
            return false;
        }

    }

    public function update($id, $username, $name, $password, $phone){
        $id = $this->conn->real_escape_string($id);
        $username = $this->conn->real_escape_string($username);
        $name = $this->conn->real_escape_string($name);
        $password = $this->conn->real_escape_string($password);
        $phone = $this->conn->real_escape_string($phone);

        $query = "UPDATE $this->table SET username='$username', name='$name', password='$password', phone=$phone where id=$id";
        $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }

    }
}