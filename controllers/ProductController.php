<?php

namespace app\controllers;

use app\core\Controller;
use app\models\ProductsModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class ProductController extends Controller
{
    private $db;
    private $table = "product";
    private Application $app;
    private $imageDest;

    public function __construct()
    {
        $this->db = new ProductsModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("products/addProduct");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["outofstock"]) && isset($_POST["publish"]) && isset($_POST["popular"]) && isset($_POST["description"]) && isset($_POST["range"]) && isset($_POST["price"]) && isset($_POST["discount"]) && isset($_FILES['productimage'])) {
                    $imageResult = $this->validateImage();
                    if ($imageResult == true) {
                        if ($this->db->create("product", $_POST["productName"], $this->imageDest, $_POST["sellerName"], $_POST["category"], $_POST["subCategory"], $_POST["outofstock"], $_POST["publish"], $_POST["description"], $_POST["unit"], $_POST["price"], $_POST["discount"], $_POST['popular'])) {
                            $msg = urlencode("New product created successfully!");
                            return header("Location: /productlist?msg=$msg");
                        } else {
                            throw new Exception("Unable to add a new product!");
                        }
                    } else {
                        throw new Exception($imageResult);
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /productlist/add?msg=$msg");
            }
        }
    }

    public function read()
    {
        $json = $this->db->read($this->table);
        try {
            if ($json) {
                return $this->render("products/productList", $json);
            } else {
                throw new Exception("No products found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function update()
    {
        try {
            if ($this->app->request->getMethod() === "post") {
                if (isset($_POST["id"]) && isset($_POST["productName"]) && isset($_POST["sellerName"]) && isset($_POST["category"]) && isset($_POST["subCategory"]) && isset($_POST["outofstock"]) && isset($_POST["publish"]) && isset($_POST["popular"]) && isset($_POST["description"]) && isset($_POST["range"]) && isset($_POST["price"]) && isset($_POST["discount"])) {
                    $validateImage = NULL;
                    if (isset($_FILES['productimage']['name']) && $_FILES['productimage']['name'] != "") {
                        $validateImage = $this->validateImage();
                    }
                    if ($validateImage === true || $validateImage == NULL) {
                        if ($this->db->update("product", $_POST['id'], $_POST["productName"], $_POST["sellerName"], $_POST["category"], $_POST["subCategory"], $_POST["outofstock"], $_POST["publish"], $_POST["description"], $_POST["range"], $_POST["price"], $_POST["discount"], $_POST['popular'], $validateImage == true ? $this->imageDest : "")) {
                            $msg = urlencode("Product updated successfully!");
                            return header("Location: /productlist?msg=$msg");
                        } else {
                            throw new Exception("Unable to update product!");
                        }
                    }
                } else {
                    throw new Exception("Invalid Arguments!");
                }
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /productlist?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("product", $_POST['id'])) {
                    return header("Location: /productlist?something=something");
                } else {
                    throw new Exception("Unable to delete product!");
                }
            } else {
                throw new Exception("Invalid ID");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /productlist?msg=$msg");
        }
    }

    public function validateImage()
    {
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/product/";
        $targetFile = $targetDir . basename($_FILES['productimage']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['productimage']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                return "File is not an image";
            }
        }

        //check if image file already exist
        if (file_exists($targetFile)) {
            return "Image file already exist";
            $uploadOk = 0;
        }

        // limit the file size
        if ($_FILES['productimage']['size'] > 5000000) {
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
            if (move_uploaded_file($_FILES["productimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = "/assets/images/product/" . basename($_FILES['productimage']['name']);
                return true;
            } else {
                return false;
            }
        }
    }
}
