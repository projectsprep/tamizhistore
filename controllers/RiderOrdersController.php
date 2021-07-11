<?php

namespace app\controllers;

use app\core\Controller;
use app\models\DB;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class RiderOrdersController extends Controller{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }
    public function read(){
        $query = "SELECT rnoti.*, rider.name FROM rnoti INNER JOIN rider on rnoti.rid=rider.id where rnoti.status='accepted' order by id desc";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            $json = json_encode($array);
            return $this->render("deliveryboys/riderOrders", $json);
        }else{
            $msg = urlencode("No Orders found for riders!");
            return header("Location: /?msg=$msg");
        }
    }
}