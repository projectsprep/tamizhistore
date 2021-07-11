<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class SubCategoryModel
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

    public function create($table, $subcategoryName, $subcategoryImage, $category, $deliverycharge, $address, $status)
    {
        $table = $this->conn->real_escape_string($table);
        $subcategoryName = $this->conn->real_escape_string($subcategoryName);
        $subcategoryImage = $this->conn->real_escape_string($subcategoryImage);
        $category = $this->conn->real_escape_string($category);
        $deliverycharge = $this->conn->real_escape_string($deliverycharge);
        $address = $this->conn->real_escape_string($address);
        $status = $this->conn->real_escape_string($status);

        $query = "INSERT INTO $table (`name`, img, cat_id, deliverycharge, address, status) VALUES ('$subcategoryName', '$subcategoryImage', $category, $deliverycharge, '$address', $status);";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getCount()
    {
        $tables = ['category', "subcategory", "product", "tbl_coupon", "area_db", "timeslot", "user", "feedback", "code", "rider", "noti", "rate_order", "orders"];
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
            $query = "SELECT COUNT(id) $stat FROM orders where status='$stat'";
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($array, $row);
                }
            }
        }

        return json_encode($array);
    }

    public function getSubCategoryById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "Select s.*, category.catname from $table s inner join category on s.cat_id = category.id  where s.id=$id ORDER BY s.id DESC";
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

    public function getSubCategoryByCid($table, $cid)
    {
        $table = $this->conn->real_escape_string($table);
        $cid = $this->conn->real_escape_string($cid);

        $query = "Select s.id, s.cat_id, s.name, s.img, category.catname from $table s inner join category on s.cat_id = category.id  where s.cat_id=$cid ORDER BY s.id DESC";
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

    public function read($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "Select s.*, category.catname from $table s left join category on s.cat_id = category.id ORDER BY s.id DESC";
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

        $query = "Select s.*, category.catname from $table s left join category on s.cat_id = category.id where category.status = 1 and s.status = 1 ORDER BY s.id DESC";
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

    public function update($table, $id, $cat_id, $name, $deliverycharge, $address, $status, $img = "")
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);
        $cat_id = $this->conn->real_escape_string($cat_id);
        $name = $this->conn->real_escape_string($name);
        $img = $this->conn->real_escape_string($img);
        $address = $this->conn->real_escape_string($address);
        $deliverycharge = $this->conn->real_escape_string($deliverycharge);
        $status = $this->conn->real_escape_string($status);

        if (isset($img) && $img != "") {
            $query = "UPDATE $table set name='$name', deliverycharge=$deliverycharge, status=$status, address='$address', img='$img', cat_id='$cat_id' where id=$id";
        } else {
            $query = "UPDATE $table set name='$name', deliverycharge=$deliverycharge, status=$status, address='$address', cat_id='$cat_id' where id=$id";
        }
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
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
