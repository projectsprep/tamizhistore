<?php

namespace app\controllers;

use app\core\Controller;
use app\models\AreaModel;
use app\core\Application;
use Exception;

session_start();

if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class AreaController extends Controller{
    private $db;
    private $app;
    public function __construct(){
        $this->db = new AreaModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read(){
        $json = $this->db->read("area_db");
        try{
            if($json){
                return $this->render("area/areaList", $json);
            }else{
                throw new Exception("Unable to fetch data. Please try again later");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function create(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("area/addArea");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['areaName']) && isset($_POST['delCharge']) && isset($_POST['status'])){
                if($this->db->create('area_db', $_POST['areaName'], $_POST['delCharge'], $_POST['status'])){
                   $msg = "Added a new Area!"; 
                    return header("Location: /arealist?msg=$msg");
                }else{
                    $msg = urlencode("Unable to add new Area");
                    return header("Location: /arealist?msg=$msg");
                }
            }else{
                $msg = urlencode("All fields are required");
                return header("Location: /arealist?msg=$msg");
            }
        }
    }

    public function edit(){
        if(isset($_POST['areaName']) && isset($_POST['id']) && isset($_POST['delCharge']) && isset($_POST['status'])){
            if($this->db->update("area_db", $_POST['id'], $_POST['areaName'], $_POST['delCharge'], $_POST['status'])){
                $msg = urlencode("Area updated!");
                return header("Location: /arealist?msg=$msg");
            }else{
                $msg = urlencode("Unable to update Area");
                return header("Location: /arealist?msg=$msg");
            }
        }else{
            return "All fields are required";
        }
    }

    public function delete(){
        try{
            if(isset($_POST['id'])){
                if($this->db->delete("area_db", $_POST['id'])){
                    $msg = urlencode("Area deleted");
                    return header("Location: /arealist?msg=$msg");
                }
            }else{
                throw new Exception("Invalid ID");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /arealist?msg=$msg");
        }
    }
}