<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\DB;
use Exception;

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
            try{
                if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pass'])){
                    $uName = $_SESSION['user'];
                    $result = $this->db->query("SELECT * FROM admin where username='$uName'");
                    if($result->num_rows > 0){
                        $array = [];
                        if($row = $result->fetch_assoc()){
                            if(($_POST['username'] == $row['username']) && ($_POST['email'] == $row['email']) && ($_POST['pass'] == $row['password'])){
                                throw new Exception("No fields are changed to update profile!");
                            }else{
                                $username = $this->db->real_escape_string($_POST['username']);
                                $email = $this->db->real_escape_string($_POST['email']);
                                $password = $this->db->real_escape_string($_POST['pass']);
                                $query = $this->db->query("UPDATE admin SET username='$username', email='$email', password='$password' where username='$uName'");
                                if($query){
                                    $_SESSION['user'] = $username;
                                    $msg = urlencode("User Profile updated!");
                                    return header("Location: /profile?msg=$msg");
                                }else{
                                    throw new Exception("Unable to update user profile!");
                                }
                            }
                        }
                    }
                }else{
                    throw new Exception("All input fields are required!");
                }
            }catch(Exception $e){
                $msg = urlencode($e->getMessage());
                return header("Location: /profile?msg=$msg");
            }
        }
    }
}