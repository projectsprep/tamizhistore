<?php

namespace app\controllers;

use app\core\Controller;
use app\models\StepsInfoModel;
use Exception;
use app\core\Application;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class StepsInfoController extends Controller{
    private $db;
    private $app;
    private $imageDest;

    public function __construct()
    {   
        $this->db = new StepsInfoModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function getstepsinfo(){
        if(isset($_GET['id']) && ($_GET['id'] != "")){
            $result = $this->db->getStepsInfoById($_GET['id']);
            if($result){
                return json_encode($result);
            }else{
                http_response_code(400);
                return json_encode("No Items found!");
            }
        }else{
            return json_encode("Invalid arguments!");
        }
    }

    public function read(){
        try{
            $data = $this->db->read();

            if($data){
                return $this->render("stepsInfo/stepsInfoList", $data);
            }else{
                throw new Exception("No Informations found. Try adding a new item into the list!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function add(){
        try{
            if($this->app->request->getMethod() === "get"){
                return $this->render("stepsInfo/addStepsInfo");
            }else if($this->app->request->getMethod() === "post"){
                if(isset($_POST['msg']) && isset($_FILES['image']['name']) && ($_POST['msg'] != "") && ($_FILES['image']['name'] != "")){
                    $validateImage = $this->validateImage();
                    if($validateImage === true){
                        $result = $this->db->create($this->imageDest, $_POST['msg']);
                        if($result){
                            throw new Exception("Added new Information successfully!");
                        }else{
                            throw new Exception("Unable to add information!");
                        }
                    }else{
                        throw new Exception($validateImage);
                    }
                }else{
                    throw new Exception("All input fields are required!");
                }
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /stepsinfo?msg=$msg");
        }
    }

    public function update(){
        try{
            if(isset($_POST['id']) && isset($_POST['msg']) && isset($_FILES['image']['name']) && ($_POST['id'] != "") && ($_POST['msg'] != "")){
                $validateImage = NULL;
                if ($_FILES['image']['name'] != "") {
                    $validateImage = $this->validateImage();
                }
                $result = $this->db->update($validateImage === true ? $this->imageDest : "", $_POST['msg'], $_POST['id']);
                if($result){
                    throw new Exception("Information updated successfully!");
                }else{
                    throw new Exception("Unable to update information!");
                }
            }else{
                throw new Exception("All input fields are required!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /stepsinfo?msg=$msg");
        }
    }

    public function delete(){
        try{
            if(isset($_POST['id']) && ($_POST['id'] != "")){
                $result = $this->db->delete($_POST['id']);
                if($result){
                    throw new Exception("Information deleted successfully!");
                }else{
                    throw new Exception("Unable to delete the information!");
                }
            }else{
                throw new Exception("All input fields are required!");
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /stepsinfo?msg=$msg");
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
        
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/stepsinfo/";
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
                $this->imageDest = sprintf("/assets/images/stepsinfo/%s.%s", $fileName, pathinfo($_FILES['image']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }

}