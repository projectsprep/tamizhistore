<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class CategoryModel
{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function create($table, $categoryName, $categoryImage, $status)
    {
        $table = $this->conn->real_escape_string($table);
        $categoryName = $this->conn->real_escape_string($categoryName);
        $categoryImage = $this->conn->real_escape_string($categoryImage);
        $status = $this->conn->real_escape_string($status);

        $query = "INSERT INTO $table (catname, catimg, status) VALUES ('$categoryName', '$categoryImage', $status);";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {

            return false;
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function getCount()
    {
        $tables = ['category', "subcategory", "product", "tbl_coupon", "area_db", "timeslot", "users", "feedback", "code", "rider", "noti", "rate_order", "temporders", "bookings", "productrequest"];
        $array = [];
        foreach ($tables as $table) {
            $query = "SELECT COUNT(id) $table FROM $table";
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($array, $row);
                }
            }
        }

        $status = ['pending', 'cancelled'];
        foreach ($status as $stat) {
            $query = "SELECT COUNT(id) $stat FROM temporders where orderstatus='$stat'";
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($array, $row);
                }
            }
        }

        return json_encode($array);
    }

    public function read($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "Select * from $table ORDER BY id DESC";
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

    public function apiRead($table){
        $table = $this->conn->real_escape_string($table);

        $query = "Select * from $table where status = 1 ORDER BY id DESC";
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

    public function getCategoryById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);
        $query = "SELECT * FROM $table WHERE id = $id";
        $result = $this->conn->query($query);
        $array = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return json_encode($array);
        } else {
            return json_encode("No results found");
        }
    }

    public function update($table, $id, $catname, $status, $catimage = "")
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);
        $catname = $this->conn->real_escape_string($catname);
        $catimage = $this->conn->real_escape_string($catimage);
        $status = $this->conn->real_escape_string($status);

        if (isset($catimage) && $catimage != "") {
            $query = "UPDATE $table set catname='$catname', status=$status, catimg='$catimage' where id=$id";
        } else {
            $query = "UPDATE $table set catname='$catname', status=$status where id=$id";
        }
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
        if ($result == 1) {

            return true;
        } else {

            return false;
        }
    }
}
