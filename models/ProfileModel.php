<?php

namespace app\models;
use mysqli;
class ProfileModel{
    private $conn = null;

    public function __construct(){
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function login($username, $pass){
        $query = "SELECT * FROM admin where username='$username'";
        $result = $this->conn->query($query);
        if($result->num_rows == 0){
            return false;
        }else{
            while($row = $result->fetch_assoc()){
                if($pass === $row['password']){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    // public function verifyToken($user){
    //     $query = "SELECT * FROM login_access where username='$user'";
    //     $result = $this->conn->query($query);

    //     // if($result > 0){
    //     //     while($row = $result->fetch_assoc()){
    //     //         if()
    //     //     }
    //     // }
    // }

}