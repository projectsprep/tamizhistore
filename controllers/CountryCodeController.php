<?php

namespace app\controllers;

use app\core\Controller;
use app\models\CountryCodeModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class CountryCodeController extends Controller
{
    private $db;
    private $app;

    public function __construct()
    {
        $this->db = new CountryCodeModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read()
    {
        $json = $this->db->read("code");
        try {
            if ($json) {
                return $this->render("countrycode/codeList", $json);
            } else {
                return $this->render("countrycode/codeList", "", json_encode(array("No items found!")));
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function getCodelist()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->db->getCodeById("code", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->db->read("code");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("countrycode/addCode");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['cc']) && isset($_POST['codeStatus'])) {
                    $status = $_POST['codeStatus'] == "publish" ? 1 : 0;
                    if ($this->db->create('code', $_POST['cc'], $status)) {
                        $msg = urlencode("CountryCode added successfully!");
                        return header("Location: /countrycode?msg=$msg");
                    } else {
                        throw new Exception("Unable to add new country code!");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /countrycode/code?msg=$msg");
            }
        }
    }

    public function update()
    {
        try {
            if (isset($_POST['id']) && isset($_POST['cc']) && isset($_POST['codeStatus'])) {
                if ($this->db->update("code", $_POST['id'], $_POST['cc'], $_POST['codeStatus'])) {
                    $msg = urlencode("Updated CountryCode!");
                    return header("Location: /countrycode?msg=$msg");
                } else {
                    throw new Exception("Unable to update country code!");
                }
            } else {
                throw new Exception("All input fields are required!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /countrycode?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("code", $_POST['id'])) {
                    $msg = "Deleted CountryCode!";
                    return header("Location: /countrycode?msg=$msg");
                } else {
                    throw new Exception("Unable to delete country code!");
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /countrycode?msg=$msg");
        }
    }
}
