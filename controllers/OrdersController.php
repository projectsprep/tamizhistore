<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\models\OrdersModel;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class OrdersController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = new OrdersModel();
    }
    public function read()
    {
        $json = $this->db->read();
        try {
            if ($json) {
                return $this->render("orders/ordersList", $json);
            } else {
                throw new Exception("No orders found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function readPending()
    {
        $json = $this->db->readPending();
        try {
            if ($json) {
                return $this->render("orders/pendingOrderList", $json);
            } else {
                throw new Exception("Unable to fetch pending orders data. Please try again later!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /orders?msg=$msg");
        }
    }

    public function exportOrders()
    {
        $json = $this->db->read();
        try {
            if ($json) {
                return $this->render("orders/exportOrders", $json);
            } else {
                throw new Exception("Unable to fetch data. Please try again later!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /orders?msg=$msg");
        }
    }
}
