<?php

namespace app\controllers;

use app\core\Controller;
use app\models\TimeSlotsModel;
use app\core\Application;

use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class TimeSlotsController extends Controller
{
    private $db;
    private $app;

    public function __construct()
    {
        $this->db = new TimeSlotsModel();
        $this->app = new Application(dirname(__DIR__));
    }
    public function read()
    {
        $json = $this->db->read("timeslot");
        try {
            if ($json) {
                return $this->render("timeslots/tslists", $json);
            } else {
                throw new Exception("No timeslots found. Try adding a new item into list!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("timeslots/addTimeslots");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if (isset($_POST['minTime']) && isset($_POST['maxTime'])) {
                    if ($this->db->create('timeslot', $_POST['minTime'], $_POST['maxTime'])) {
                        $msg = urlencode("Added new timeslot!");
                        return header("Location: /timeslots?msg=$msg");
                    } else {
                        throw new Exception("Unable to add new timeslot!");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                $msg = urlencode($e->getMessage());
                return header("Location: /timeslots/add?msg=$msg");
            }
        }
    }

    public function edit()
    {
        try {
            if (isset($_POST['id']) && isset($_POST['minTime']) && isset($_POST['maxTime'])) {
                if ($this->db->update("timeslot", $_POST['id'], $_POST['minTime'], $_POST['maxTime'])) {
                    $msg = urlencode("Updated timeslot!");
                    return header("Location: /timeslots?msg=$msg");
                } else {
                    throw new Exception("Unable to update timeslot!");
                }
            } else {
                throw new Exception("All fields are required!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /timeslots?msg=$msg");
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("timeslot", $_POST['id'])) {
                    $msg = "Deleted Timeslot!";
                    return header("Location: /timeslots?msg=$msg");
                } else {
                    $msg = urlencode("Unable to delete timeslot!");
                    return header("Location: /timeslots?msg=$msg");
                }
            } else {
                throw new Exception("Invalid ID");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            return header("Location: /timeslots?msg=$msg");
        }
    }
}
