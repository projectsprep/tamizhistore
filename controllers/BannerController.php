<?php

namespace app\controllers;

use app\core\Controller;
use app\models\BannerModel;
use Exception;
use app\core\Application;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}


class BannerController extends Controller{
    private $db;
    private $imageDest;
    private $app;

    public function __construct()
    {   
        $this->app = new Application(dirname(__DIR__));
        $this->db = new BannerModel();
    }

    public function getBanner(){
        try {
            if (isset($_GET['id']) && ($_GET['id'] !== '')) {
                return $this->db->getBannerById($_GET['id']);
            } else {
                throw new Exception("Invalid ID");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function read(){
        $data = $this->db->read();
        if($data){
            return $this->render("banner/bannerList", $data);
        }else{
            $msg = urlencode("No Banner found!. Try adding a new item into the list.");
            return header("Location: /?msg=$msg");
        }
    }

    public function update(){
        try{
            if(isset($_POST['cid']) && isset($_POST['sid']) && isset($_FILES['image']['name']) && isset($_POST['id'])){
                if(($_POST['cid'] != "") && ($_POST['sid'] != "") && ($_POST['id'] != "")){
                    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                        $validateImage = $this->validateImage();
                    }         
                    if ($validateImage === true || $validateImage == NULL) {
                        $result = $this->db->update($_POST['id'], $_POST['cid'], $_POST['sid'], $validateImage == true ? $this->imageDest : "");
                        if($result){
                            throw new Exception("Banner Updated successfully!");
                        }else{
                            throw new Exception("Unable to update banner!");
                        }    
                    }else{
                        throw new Exception($validateImage);
                    }
                }else{
                    throw new Exception("All input fields are required!");
                }
            }else{
                throw new Exception("All input fields are required!");
            }
        }catch(\Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /bannerlist?msg=$msg");
        }
    }

    public function delete(){
        try{
            if(isset($_POST['id']) && ($_POST['id'] != "")){
                $result = $this->db->delete($_POST['id']);
                if($result){
                    throw new Exception("Deleted Banner successfully!");
                }else{
                    throw new Exception("Unable to delete banner!");
                }
            }else{
                throw new Exception("All input fields are required!");
            }    
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /bannerlist?msg=$msg");
        }
    }

    public function create(){
        try{
            if($this->app->request->getMethod() === "get"){
                return $this->render("banner/addBanner");
            }else if($this->app->request->getMethod() === "post"){
                if(isset($_POST['cid']) && isset($_POST['sid']) && isset($_FILES['image']['name'])){
                    $validateImage = $this->validateImage();
                    if($validateImage === true){
                        $result = $this->db->create($_POST['cid'], $_POST['sid'], $this->imageDest);
                        if($result){
                            throw new Exception("Added new Banner successfully!");
                        }else{
                            throw new Exception("Unable to add new banner!");
                        }
                    }else{
                        throw new Exception($validateImage);
                    }
                }else{
                    throw new Exception("All input fields are requried!");
                }   
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /bannerlist?msg=$msg");
        }
    }

    public function validateImage()
    {
        function generateRandomString($length = 25) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $fileName = generateRandomString(15);
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/banner/";
        $targetFile = sprintf("%s%s.%s", $targetDir, $fileName, pathinfo($_FILES['image']['name'])['extension']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                return "File is not an image";
            }
        }

        //check if image file already exist
        if (file_exists($targetFile)) {
            $this->validateImage();
        }

        // limit the file size
        if ($_FILES['image']['size'] > 5000000) {
            $uploadOk = 0;
            return "File size too large";
        }

        if ($imageFileType != "jpg" && $imageFileType != 'png' && $imageFileType != "jpeg") {
            $uploadOk = 0;
            return "Only jpg, png and jpeg file formats are allowed";
        }

        if ($uploadOk == 0) {
            return "Your file didn't uploaded for some reasons";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $this->imageDest = sprintf("banner/%s.%s", $fileName, pathinfo($_FILES['image']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }
}