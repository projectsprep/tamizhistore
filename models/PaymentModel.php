<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class PaymentModel
{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "Select * from $table";
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

    public function getPaymentById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);

        $query = "SELECT * FROM $table where id=$id";
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

    public function update($table, $id, $gateway, $title, $value, $status)
    {
        $table = $this->conn->real_escape_string($table);
        $gateway = $this->conn->real_escape_string($gateway);
        $title = $this->conn->real_escape_string($title);
        $value = $this->conn->real_escape_string($value);
        $status = $this->conn->real_escape_string($status);
        $id = htmlspecialchars($id);

        $query = "UPDATE $table set title='$gateway', cred_title='$title', cred_value='$value', `status`='$status' where id=$id";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "SELECT * FROM $table where id=$id";
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
}
