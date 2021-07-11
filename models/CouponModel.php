<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class CouponModel
{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function create($table, $couponimage, $expiryDate, $couponCode, $couponTitle, $couponStatus, $minAmt, $discount, $description)
    {
        $table = $this->conn->real_escape_string($table);
        $couponimage = $this->conn->real_escape_string($couponimage);
        $expiryDate = $this->conn->real_escape_string($expiryDate);
        $couponCode = $this->conn->real_escape_string($couponCode);
        $couponTitle = $this->conn->real_escape_string($couponTitle);
        $couponStatus = $this->conn->real_escape_string($couponStatus);
        $minAmt = $this->conn->real_escape_string($minAmt);
        $discount = $this->conn->real_escape_string($discount);
        $description = $this->conn->real_escape_string($description);

        $query = "INSERT INTO $table (c_img, cdate, c_title, ctitle, `status`, min_amt, c_value, c_desc) VALUES ('$couponimage', '$expiryDate', '$couponCode', '$couponTitle', '$couponStatus', '$minAmt', '$discount', '$description');";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function read($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "Select * from $table order by id desc";
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

    public function getCouponById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "SELECT * FROM $table WHERE id = $id";
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

    public function update($table, $id, $expiry, $code, $title, $status, $minAmt, $discount, $description)
    {
        $table = $this->conn->real_escape_string($table);
        $expiry = $this->conn->real_escape_string($expiry);
        $code = $this->conn->real_escape_string($code);
        $title = $this->conn->real_escape_string($table);
        $status = $this->conn->real_escape_string($status);
        $minAmt = $this->conn->real_escape_string($minAmt);
        $discount = $this->conn->real_escape_string($discount);
        $description = $this->conn->real_escape_string($description);
        $id = htmlspecialchars($id);

        $query = "UPDATE $table set cdate='$expiry', c_desc='$description', c_value='$discount', c_title='$code', ctitle='$title', status='$status', min_amt='$minAmt' where id=$id";
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

    public function delete($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
