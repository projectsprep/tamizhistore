<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class NotificationsModel
{
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function create($table, $categoryName, $categoryImage)
    {
        $table = $this->conn->real_escape_string($table);
        $categoryName = $this->conn->real_escape_string($categoryName);
        $categoryImage = $this->conn->real_escape_string($categoryImage);

        $query = "INSERT INTO $table (catname, catimg) VALUES ('$categoryName', '$categoryImage');";
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

    public function pushedNotifies($table)
    {
        $table = $this->conn->real_escape_string($table);

        $query = "Select * from $table where pushed=1 ORDER BY id DESC";
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

    public function update($table, $id, $catname, $catimage)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);
        $catname = $this->conn->real_escape_string($catname);
        $catimage = $this->conn->real_escape_string($catimage);

        $query = "UPDATE $table set catname='$catname', catimg='$catimage' where id=$id";
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

    public function push($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = $this->conn->real_escape_string($id);

        $query = "update $table set pushed=1, duration=NOW() where id = $id";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
