<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\DB;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class UserProfileController extends Controller{
    private $db = null;
    private $app;
    public function __construct(){
        $this->db = new DB();
        $this->db = $this->db->conn();
        $this->app = new Application(dirname(__DIR__));
    }
    
    public function profile(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("user/userProfile");
        }else if($this->app->request->getmethod() === "post"){
            if($_POST['username'] && $_POST['email']){
                $uName = $_SESSION['user'];
                $result = $this->db->query("SELECT * FROM admin where username='$uName'");
                if($result->num_rows > 0){
                    $array = [];
                    if($row = $result->fetch_assoc()){
                        if(($_POST['username'] == $row['username']) && ($_POST['email'] == $row['email'])){
                            echo "its same";
                            return header("Refresh: 2; URL=/profile");
                        }else{
                            $username = $this->db->real_escape_string($_POST['username']);
                            $email = $this->db->real_escape_string($_POST['email']);
                            $query = $this->db->query("UPDATE admin SET username='$username', email='$email' where username='$uName'");
                            if($query){
                                echo "Profile updated";
                                $_SESSION['user'] = $username;
                                return header("Refresh: 2; URL=/profile");
                            }
                        }
                    }
                }
            }
        }
    }
}