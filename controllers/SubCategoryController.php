<?php

namespace app\controllers;

use app\core\Controller;
use app\models\SubCategoryModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class SubCategoryController extends Controller
{
    private $db;
    private $imageDest;
    private Application $app;
    public function __construct()
    {
        $this->db = new SubCategoryModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function getSubCategories()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->db->getSubCategoryById("subcategory", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID!");
                }
            } else {
                return $this->db->apiRead("subcategory");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("subcategories/addSubcategory");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['subcategoryname']) && ($_POST['subcategoryname'] !== "") && isset($_POST['shopstatus']) && ($_POST['shopstatus'] !== "") && isset($_POST['charge']) && ($_POST['charge'] !== "") && isset($_POST['category']) && ($_POST['category'] !== "") && isset($_POST['doorno']) && ($_POST['doorno'] !== "") && isset($_POST['addr1']) && ($_POST['addr1'] !== "") && isset($_POST['addr2']) && ($_POST['addr2'] !== "") && isset($_POST['pincode']) && ($_POST['pincode'] !== "")  && isset($_FILES['subcategoryimage']['name']) && $_FILES['subcategoryimage']['name'] != "") {
                    $validateImage = $this->validateImage();
                    if ($validateImage === true) {
                        $address = [$_POST['doorno'], $_POST['addr1'], $_POST['addr2'], $_POST['pincode']];
                        $address = implode("|", $address);
                        if ($this->db->create("subcategory", $_POST["subcategoryname"], $this->imageDest, $_POST['category'], $_POST['charge'], $address, $_POST['shopstatus'])) {
                            $msg = urlencode("Added subcategory successfully!");
                            return header("Location: /subcategorylist?msg=$msg");
                        } else {
                            throw new Exception("Unable to add new subcategory!");
                        }
                    } else {
                        throw new Exception($validateImage);
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /subcategorylist/add?msg=$msg");
            }
        }
    }

    public function read()
    {
        $json = $this->db->read("subcategory");
        try {
            if ($json) {
                return $this->render("subcategories/subCategoryList", $json);
            } else {
                throw new Exception("No subcategories found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("subcategory", $_POST['id'])) {
                    return header("Location: /subcategorylist");
                } else {
                    $msg = urlencode("Unable to delete a category.");
                    return header("Location: /subcategorylist?msg=$msg");
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /subcategorylist?msg=$msg");
        }
    }

    public function update()
    {
        try {
            if ($this->app->request->getMethod() === "post") {
                if (isset($_POST['id']) && isset($_POST['subcategoryName']) && isset($_POST['category']) && isset($_POST['charge']) && isset($_POST['doorno']) && ($_POST['doorno'] !== "") && isset($_POST['shopstatus']) && ($_POST['shopstatus'] !== "") && isset($_POST['addr1']) && ($_POST['addr1'] !== "") && isset($_POST['addr2']) && ($_POST['addr2'] !== "") && isset($_POST['pincode']) && ($_POST['pincode'] !== "")) {
                    $validateImage = NULL;
                    if (isset($_FILES['subcategoryimage']['name']) && $_FILES['subcategoryimage']['name'] != "") {
                        $validateImage = $this->validateImage();
                    }
                    if ($validateImage === true || $validateImage == NULL) {
                        $address = [$_POST['doorno'], $_POST['addr1'], $_POST['addr2'], $_POST['pincode']];
                        $address = implode("|", $address);
                        if ($this->db->update("subcategory", $_POST['id'], $_POST['category'], $_POST['subcategoryName'], $_POST['charge'], $address, $_POST['shopstatus'], $validateImage == true ? $this->imageDest : "")) {
                            $msg = urlencode("SubCategory updated successfully!");
                            return header("Location: /subcategorylist?msg=$msg");
                        } else {
                            throw new Exception("Unable to update SubCategory!");
                        }
                    } else {
                        throw new Exception($validateImage);
                    }
                } else {
                    throw new Exception("Unable to update SubCategory!");
                }
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /subcategorylist?msg=$msg");
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

        $targetDir = Application::$ROOT_DIR . "/public/assets/images/cat/";
        $targetFile = sprintf("%s%s.%s", $targetDir, $fileName, pathinfo($_FILES['subcategoryimage']['name'])['extension']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['subcategoryimage']['tmp_name']);
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
        if ($_FILES['subcategoryimage']['size'] > 5000000) {
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
            if (move_uploaded_file($_FILES["subcategoryimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = sprintf("cat/%s.%s", $fileName, pathinfo($_FILES['subcategoryimage']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }
}
