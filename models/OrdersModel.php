<?php

namespace app\models;

use app\models\DB;

class OrdersModel
{
    private $conn = null;
    private $table = "temporders";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }
    public function read()
    {
        $table = $this->conn->real_escape_string($this->table);
        $query = "SELECT o.*, p.pname, p.pprice, r.name rider FROM $this->table o LEFT join rider r on o.riderid=r.id LEFT JOIN product p on p.id=o.productid order by orderdate desc";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }

            return json_encode($array);
        } else {
            return false;
        }
    }

    public function readPending()
    {
        $table = $this->conn->real_escape_string($this->table);
        $query = "SELECT o.*, p.pname, p.pprice, r.name rider FROM $this->table o LEFT join rider r on o.riderid=r.id LEFT JOIN product p on p.id=o.productid where orderstatus='pending' order by orderdate desc";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }

            return json_encode($array);
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
