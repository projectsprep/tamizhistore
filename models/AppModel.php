<?php

namespace app\models;

use app\models\DB;

class AppModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function orders($table, $uid)
    {
        $table = $this->conn->real_escape_string($table);
        $uid = $this->conn->real_escape_string($uid);
        $query = "SELECT oid FROM `$table` WHERE uid=$uid and status='pending' and r_status='Not assigned'";
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

    public function deliveryBoy($table, $rid = "")
    {
        $table = $this->conn->real_escape_string($table);
        $rid = $this->conn->real_escape_string($rid);
        if (isset($rid) && $rid != "") {
            $query = "SELECT id FROM `rider` WHERE status=1 and is_available=1 and not id = $rid";
        } else {
            $query = "SELECT id FROM `rider` WHERE status=1 and is_available=1";
        }
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }

            shuffle($array);
            return json_encode($array[0]);
        } else {
            return false;
        }
    }

    public function deliveryBoyNoti($table, $riderID, $orderID)
    {
        $table = $this->conn->real_escape_string($table);
        $orderID = $this->conn->real_escape_string($orderID);
        $riderID = $this->conn->real_escape_string($riderID);

        $query = "INSERT INTO $table SET rid=$riderID, msg='$orderID', `date`=NOW()";
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deliveryBoyAccept($uid, $rid)
    {
        $uid = $this->conn->real_escape_string($uid);
        $rid = $this->conn->real_escape_string($rid);

        $query = "UPDATE orders SET r_status='assigned' where `uid`=$uid and rid=$rid and r_status='pending'";
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            $query = "UPDATE rnoti SET `status`='accepted' where rid=$rid and `status`='pending'";
            $result = $this->conn->query($query);
            if ($this->conn->affected_rows > 0) {
                $query = "UPDATE rider SET is_available=0 where id=$rid";
                $result = $this->conn->query($query);
                if ($this->conn->affected_rows > 0) {
                    return true;
                } else {
                    // echo "here";
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function deliveryBoyDecline($uid, $rid)
    {
        $uid = $this->conn->real_escape_string($uid);
        $rid = $this->conn->real_escape_string($rid);

        $query = "UPDATE orders SET r_status='Not assigned', rid=0 where uid=$uid and rid=$rid and r_status='pending'";
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            $query = "UPDATE rnoti SET `status`='declined' where rid=$rid and `status`='pending'";
            $result = $this->conn->query($query);
            if ($this->conn->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return "No delivery boys available!";
        }
    }

    public function assignedOrders()
    {
        $query = "SELECT rid, uid, pname, oid FROM orders WHERE r_status='assigned'";
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
