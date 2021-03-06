<?php

namespace app\controllers;

use app\core\Controller;
use app\models\ProductRequestModel;
use app\models\BookingsModel;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}


class ProductRequestController extends Controller{
    private $db;
    private $bookingsDB;

    public function __construct()
    {
        $this->db = new ProductRequestModel();
        $this->bookingsDB = new BookingsModel();
    }

    public function read(){
        $json = $this->db->read();

        if($json){
            return $this->render("productrequest/list", $json);
        }else{
            return $this->render("productrequest/list", "", json_encode(array("No items found!")));
        }
    }

    public function readBookings(){
        $json = $this->bookingsDB->getAllBookings();

        if($json){
            return $this->render("bookings/bookingslist", $json);
        }else{
            return $this->render("bookings/bookingslist", "", json_encode(array("No items found!")));
        }
    }

}