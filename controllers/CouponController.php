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

    public function read()
    {
        $json = $this->db->read("tbl_coupon");
        try {
            if ($json) {
                return $this->render("coupon/couponList", $json);
            } else {
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /couponlist?msg=$msg");
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
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/coupon/";
        $targetFile = $targetDir . basename($_FILES['couponimage']['name']);
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
            return "Image file already exist";
            $uploadOk = 0;
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
                $this->imageDest = "/assets/images/coupon/" . basename($_FILES['couponimage']['name']);
                return true;
            } else {
                return false;
            }
        }
    }
}
