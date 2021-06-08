<?php

namespace app\models;

use app\models\DB;

class CustomersModel
{
    private $table = 'user';
    private $conn = null;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function read()
    {
        $this->table = $this->conn->real_escape_string($this->table);

        $query = "SELECT * FROM $this->table";
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

    public function readFeedback()
    {
        $query = "SELECT f.*, u.name user FROM feedback f left join user u on u.id = f.uid";
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
