<?php

namespace app\controllers;

use app\core\Controller;
use app\models\AreaModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class AreaController extends Controller
{
    private $db;
    private $app;
    public function __construct()
    {
        $this->db = new AreaModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read()
    {
        $json = $this->db->read("area_db");
        try {
            if ($json) {
                return $this->render("area/areaList", $json);
            } else {
                return $this->render("area/areaList", "", json_encode(array("error"=>"No items found!")));
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /arealist?msg=$msg");
        }
    }

    public function getArea()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->db->getAreaById("area_db", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->db->read("area_db");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("area/addArea");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['areaName']) && isset($_POST['delCharge']) && isset($_POST['status'])) {
                    if ($this->db->create('area_db', $_POST['areaName'], $_POST['delCharge'], $_POST['status'])) {
                        $msg = urlencode("Added a new Area!");
                        return header("Location: /arealist?msg=$msg");
                    } else {
                        throw new Exception("Unable to add new Area");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /arealist?msg=$msg");
            }
        }
    }

    public function edit()
    {
        try {
            if (isset($_POST['areaName']) && isset($_POST['id']) && isset($_POST['delCharge']) && isset($_POST['status'])) {
                if ($this->db->update("area_db", $_POST['id'], $_POST['areaName'], $_POST['delCharge'], $_POST['status'])) {
                    $msg = urlencode("Area updated!");
                    return header("Location: /arealist?msg=$msg");
                } else {
                    throw new Exception("Unable to update Area!");
                }
            } else {
                throw new Exception("All input fields are required!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /arealist?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("area_db", $_POST['id'])) {
                    $msg = urlencode("Area deleted");
                    return header("Location: /arealist?msg=$msg");
                } else {
                    throw new Exception("Unable to delete Area!");
                }
            } else {
                throw new Exception("Invalid ID!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /arealist?msg=$msg");
        }
    }
}
