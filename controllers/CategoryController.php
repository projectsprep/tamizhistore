<?php
namespace app\controllers;

use app\core\Controller;
use app\models\CategoryModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class CategoryController extends Controller
{
    private $db;
    private $imageDest;
    private Application $app;
    public function __construct()
    {
        $this->db = new CategoryModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function getCategories()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== '') {
                    return $this->db->getCategoryById("category", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->db->read("category");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function add()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("categories/addCategory");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['categoryName']) && isset($_POST['cstatus']) && ($_POST['cstatus'] != "") && isset($_FILES['categoryimage']['name']) && $_FILES['categoryimage']['name'] != "") {
                    $validateImage = $this->validateImage();
                    if ($validateImage === true) {
                        if ($this->db->create("category", $_POST["categoryName"], $this->imageDest, $_POST['cstatus'])) {
                            $msg = urlencode("Added new Category!");
                            return header("Location: /categorylist?msg=$msg");
                        } else {
                            throw new Exception("Unable to add new category!");
                        }
                    } else {
                        throw new Exception($validateImage);
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /categorylist/add?msg=$msg");
            }
        }
    }

    public function home()
    {
        $json = $this->db->getCount();
        try {
            if ($json) {
                return $this->render('home', $json);
            } else {
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function read()
    {
        $json = $this->db->read("category");
        try {
            if ($json) {
                return $this->render("categories/categoryList", $json);
            } else {
                return $this->render("categories/categoryList", "", json_encode(array("No items found!")));
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
                if ($this->db->delete("category", $_POST['id'])) {
                    return header("Location: /categorylist");
                } else {
                    $msg = urlencode("Unable to delete a category.");
                    return header("Location: /categorylist?msg=$msg");
                }
            } else {
                throw new Exception("Unable to delete a category!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /categorylist?msg=$msg");
        }
    }

    public function update()
    {
        try {
            if ($this->app->request->getMethod() === "post") {
                if (isset($_POST['id']) && isset($_POST['categoryName']) && isset($_POST['cstatus']) && ($_POST['cstatus'] != "")) {
                    $validateImage = NULL;
                    if (isset($_FILES['categoryimage']['name']) && $_FILES['categoryimage']['name'] != "") {
                        $validateImage = $this->validateImage();
                    }
                    if ($validateImage === true || $validateImage == NULL) {
                        if ($this->db->update("category", $_POST['id'], $_POST["categoryName"], $_POST['cstatus'], $validateImage == true ? $this->imageDest : "")) {
                            $msg = urlencode("Category updated successfully!");
                            return header("Location: /categorylist?msg=$msg");
                        } else {
                            $msg = urlencode("Unable to update category");
                            return header("Location: /categorylist?msg=$msg");
                            throw new Exception("Unable to update category!");
                        }
                    } else {
                        throw new Exception($validateImage);
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /categorylist?msg=$msg");
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
        $targetFile = sprintf("%s%s.%s", $targetDir, $fileName, pathinfo($_FILES['categoryimage']['name'])['extension']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['categoryimage']['tmp_name']);
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
        if ($_FILES['categoryimage']['size'] > 5000000) {
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
            if (move_uploaded_file($_FILES["categoryimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = sprintf("/assets/images/cat/%s.%s", $fileName, pathinfo($_FILES['categoryimage']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }
}
