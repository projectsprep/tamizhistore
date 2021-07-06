<?php

namespace app\controllers;

use app\core\Controller;
use app\models\PaymentModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class PaymentController extends Controller
{
    private $db;
    private $app;
    public function __construct()
    {
        $this->db = new PaymentModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function getPayment()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->db->getPaymentById("payment_list", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->db->read("payment_list");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function read()
    {
        $json = $this->db->read("payment_list");
        try {
            if ($json) {
                return $this->render("payment/paymentList", $json);
            } else {
                throw new Exception("No payments found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function update()
    {
        try {
            if (isset($_POST['id']) && isset($_POST['gateway']) && isset($_POST['title']) && isset($_POST['value']) && isset($_POST['status'])) {
                if ($this->db->update("payment_list", $_POST['id'], $_POST['gateway'], $_POST['title'], $_POST['value'], $_POST['status'])) {
                    $msg = urlencode("Updated Paymentlist!");
                    return header("Location: /paymentlist?msg=$msg");
                } else {
                    throw new Exception("Unable to update paymentlist!");
                }
            } else {
                throw new Exception("All input fields are required!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /paymentlist?msg=$msg");
        }
    }
}
