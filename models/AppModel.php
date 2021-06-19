<?php

namespace app\models;

use app\models\DB;

class AppModel
{
    private $conn;
    private $table = "temporders";

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function readCart($uid){
        $uid = $this->conn->real_escape_string($uid);
        // $query = "SELECT c.*, u.id aid FROM cart c LEFT JOIN useraddress u on u.userid=c.userid WHERE c.userid=$uid";
        $query = "SELECT c.*, (SELECT id from useraddress where userid=c.userid LIMIT 1) as aid, p.pname, p.sname, p.cid, p.sid, p.psdesc, p.pgms, p.pprice, p.status, p.stock, p.pimg, p.prel, p.date, p.discount, p.popular FROM cart c INNER JOIN product p on p.id = c.productid WHERE c.userid=$uid";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }

            return $array;
        }else{
            return false;
        }
    }

    public function read($uid){
        $uid = $this->conn->real_escape_string($uid);

        $query = "SELECT o.*, a.address, p.pname, p.sname, p.cid, p.sid, p.psdesc, p.pgms, p.pprice, p.status, p.stock, p.pimg, p.prel, p.date, p.discount, p.popular FROM $this->table o INNER JOIN product p on p.id = o.productid LEFT JOIN useraddress a ON a.id = o.addressid WHERE o.userid=$uid ORDER BY o.id DESC";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return $array;
        }else{
            return false;
        }
    }

    public function create($userid, $productid, $addressid, $qty, $note="", $address="", $oid){
        $oid = $this->conn->real_escape_string($oid);
        $userid = $this->conn->real_escape_string($userid);
        $productid = $this->conn->real_escape_string($productid);
        $addressid = $this->conn->real_escape_string($addressid);
        $qty = $this->conn->real_escape_string($qty);
        $note = $this->conn->real_escape_string($note);
        $address = $this->conn->real_escape_string($address);

        if($address == ""){
            $query = "INSERT INTO $this->table SET oid=$oid, userid=$userid, productid=$productid, addressid=$addressid, quantity=$qty, note='$note'";
        }else{
            $query = "INSERT INTO $this->table SET oid=$oid, userid=$userid, productid=$productid, addressid=0, quantity=$qty, note='$note', customaddress='$address'";
        }

        $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }

    }

    public function orders($uid)
    {
        $uid = $this->conn->real_escape_string($uid);
        $query = "SELECT id FROM $this->table WHERE userid=$uid and orderstatus='pending' and deliverystatus='pending'";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return $array;
        } else {
            return false;
        }
    }


    // public function orders($table, $uid)
    // {
    //     $table = $this->conn->real_escape_string($table);
    //     $uid = $this->conn->real_escape_string($uid);
    //     $query = "SELECT oid FROM `$table` WHERE uid=$uid and status='pending' and r_status='Not assigned'";
    //     $result = $this->conn->query($query);
    //     $array = [];
    //     if ($result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             array_push($array, $row);
    //         }
    //         return json_encode($array);
    //     } else {
    //         return false;
    //     }
    // }

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
            return $array[0];
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
