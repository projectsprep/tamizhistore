<?php

namespace App\models;

use mysqli;
use app\models\DB;

// header("Content-type: application/json");

class TimeSlotsModel
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

    public function create($table, $minTime, $maxTime)
    {
        $table = $this->conn->real_escape_string($table);
        $minTime = $this->conn->real_escape_string($minTime);
        $maxTime = $this->conn->real_escape_string($maxTime);

        $query = "INSERT INTO $table (mintime, maxtime) VALUES ('$minTime', '$maxTime');";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getTimeslotById($table, $id)
    {
        $table = $this->conn->real_escape_string($table);
        $id = htmlspecialchars($id);

        $query = "SELECT * FROM $table where id = $id";
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

    public function update($table, $id, $minTime, $maxTime)
    {
        $table = $this->conn->real_escape_string($table);
        $minTime = $this->conn->real_escape_string($minTime);
        $maxTime = $this->conn->real_escape_string($maxTime);
        $id = htmlspecialchars($id);

        $query = "UPDATE $table set mintime='$minTime', maxtime='$maxTime' where id=$id";
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
        $id = htmlspecialchars($id);

        $query = "DELETE FROM $table where id=$id";
        $result = $this->conn->query($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
