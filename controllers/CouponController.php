<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CouponModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class CouponController extends Controller
{
    private $db;
    private Application $app;
    public function __construct()
    {
        $this->db = new CouponModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("coupon/addCoupon");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_FILES['couponimage']) && ($_FILES['couponimage']['name'] != "") && isset($_POST['expiryDate']) && isset($_POST['couponCode']) && isset($_POST['couponTitle']) && isset($_POST['couponStatus']) && isset($_POST['minAmt']) && isset($_POST['discount']) && isset($_POST['description'])) {
                    $imageResult = $this->validateImage();
                    if ($imageResult == true) {
                        if ($this->db->create('tbl_coupon', $this->imageDest, $_POST['expiryDate'], $_POST['couponCode'], $_POST['couponTitle'], $_POST['couponStatus'], $_POST['minAmt'], $_POST['discount'], $_POST['description'])) {
                            $msg = urlencode("Created new coupon successfully!");
                            return header("Location: /couponlist?msg=$msg");
                        } else {
                            throw new Exception("Unable to add new Coupon!");
                        }
                    } else {
                        throw new Exception($imageResult);
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /couponlist/add?msg=$msg");
            }
        }
    }

    public function getCoupons()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->db->getCouponById("tbl_coupon", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->db->read("tbl_coupon");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function read()
    {
        $json = $this->db->read("tbl_coupon");
        try {
            if ($json) {
                return $this->render("coupon/couponList", $json);
            } else {
                throw new Exception("No coupon found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_GET['id'])) {
                if ($this->db->delete("category", $_GET['id'])) {
                    $msg = urlencode("Coupon deleted successfully!");
                    return header("Location: /couponlist?msg=$msg");
                } else {
                    throw new Exception("Unable to delete coupon!");
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /couponlist?msg=$msg");
        }
    }

    public function edit()
    {
        try {
            if ($this->app->request->getMethod() === "post") {
                if (isset($_POST['id']) && isset($_POST['expiryDate']) && isset($_POST['couponCode']) && isset($_POST['couponTitle']) && isset($_POST['couponStatus']) && isset($_POST['minAmt']) && isset($_POST['discount']) && isset($_POST['description'])) {
                    if ($this->db->update("tbl_coupon", $_POST['id'], $_POST['expiryDate'], $_POST['couponCode'], $_POST['couponTitle'], $_POST['couponStatus'], $_POST['minAmt'], $_POST['discount'], $_POST['description'])) {
                        $msg = urlencode("Coupon updated successfully!");
                        return header("Location: /couponlist?msg=$msg");
                    } else {
                        throw new Exception("Unable to update coupon!");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /couponlist?msg=$msg");
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

        $targetDir = Application::$ROOT_DIR . "/public/assets/images/coupon/";
        $targetFile = sprintf("%s%s.%s", $targetDir, $fileName, pathinfo($_FILES['couponimage']['name'])['extension']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['couponimage']['tmp_name']);
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
        if ($_FILES['couponimage']['size'] > 5000000) {
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
            if (move_uploaded_file($_FILES["couponimage"]["tmp_name"], $targetFile)) {
                $this->imageDest = sprintf("/assets/images/coupon/%s.%s", $fileName, pathinfo($_FILES['couponimage']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }
}
