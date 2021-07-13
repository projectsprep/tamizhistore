<?php

namespace app\controllers;

use app\core\Controller;
use app\models\DeliveryBoysModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class DeliveryBoysController extends Controller
{
    private $db;
    private $app;

    public function __construct()
    {
        $this->db = new DeliveryBoysModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read()
    {
        $json = $this->db->read();
        try {
            if ($json) {
                return $this->render("deliveryboys/deliveryBoysList", $json);
            } else {
                return $this->render("deliveryboys/deliveryBoysList", "", json_encode(array("No items found!")));
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("deliveryboys/addDeliveryBoys");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['name']) && isset($_POST['mobile']) && isset($_POST['username']) && isset($_POST['address']) && isset($_POST['password'])) {
                    if ($this->db->create($_POST['name'], $_POST['mobile'], $_POST['username'], $_POST['address'], $_POST['password'])) {
                        $msg = urlencode("Added new Delivery Boy!");
                        return header("Location: /deliveryboys?msg=$msg");
                    } else {
                        throw new Exception("Unable to add new Delivery Boy!");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /deliveryboys/add?msg=$msg");
            }
        }
    }

    public function update()
    {
        try {
            if (isset($_POST['id']) && isset($_POST['status'])) {
                if ($this->db->update($_POST['id'], $_POST['status'])) {
                    $msg = urlencode("Updated Delivery Boy status!");
                    return header("Location: /deliveryboys?msg=$msg");
                } else {
                    throw new Exception("Unable to update Delivery Boy Status!");
                }
            } else {
                throw new Exception("All input fields are required!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /deliveryboys?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete($_POST['id'])) {
                    $msg = "Deleted Delivery Boy!";
                    return header("Location: /deliveryboys?msg=$msg");
                } else {
                    throw new Exception("Unable to delete Delivery Boy!");
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /deliveryboys?msg=$msg");
        }
    }
}
