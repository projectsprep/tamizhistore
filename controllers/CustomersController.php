<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CustomersModel;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class CustomersController extends Controller
{
    private $db;
    private $app;
    public function __construct()
    {
        $this->db = new CustomersModel();
    }

    public function readCustomers()
    {
        $json = $this->db->read();
        try {
            if ($json) {
                return $this->render("customers/customersList", $json);
            } else {
                return $this->render("customers/customersList", "", json_encode(array("No items found!")));
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function readFeedback()
    {
        $json = $this->db->readFeedback();
        try {
            if ($json) {
                return $this->render("customers/customerFeedback", $json);
            } else {
                return $this->render("customers/customerFeedback", "", json_encode(array("No items found!")));
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }
}
