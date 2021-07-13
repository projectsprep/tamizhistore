<?php

namespace app\models;

use app\models\DB;
use Exception;

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

    public function completeOrder($rid, $oid){
        $rid = $this->conn->real_escape_string($rid);
        $oid = $this->conn->real_escape_string($oid);

        $query = "UPDATE temporders SET deliverystatus='completed', orderstatus='completed', deliverydate=NOW() WHERE riderid=$rid and oid = $oid; ";
        $query .= "UPDATE rider SET complete = complete + 1 where id=$rid";
        $result = $this->conn->multi_query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function activeOrders($rid){
        $rid = $this->conn->real_escape_string($rid);

        $query = "SELECT r.*, t.userid, t.paymentmethod, t.note, t.customaddress, t.customerphone, t.totalproductprice as productsPrice, t.deliverycharge, t.totalprice FROM rnoti r INNER JOIN temporders t on r.oid = t.oid INNER JOIN product p on t.productid = p.id WHERE r.rid = $rid and r.status='accepted' and t.deliverystatus='assigned' order by oid";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $list = [];
                array_push($list, $row);
                $list[0]['products'] = [];
                $query = "SELECT p.pname, p.sname, p.pprice, p.pgms, t.quantity, t.productid, t.subproductid, s.address as shopAddress FROM temporders t INNER JOIN product p on p.id = t.productid INNER JOIN subcategory s on s.id = p.sid where oid=".$row['oid'];
                $orderResult = $this->conn->query($query);
                if($orderResult->num_rows > 0){
                    while($orderRow = $orderResult->fetch_assoc()){
                        array_push($list[0]['products'], $orderRow);
                        if($orderRow['subproductid'] != ""){
                            $subproductQuery = "SELECT * FROM subproduct WHERE id=".$orderRow['subproductid'];
                            $subProductResult = $this->conn->query($subproductQuery);
                            if($subProductResult->num_rows > 0){
                                while($subProductRow = $subProductResult->fetch_assoc()){
                                    $list[0]['products'][0]['pprice'] = $subProductRow['price'];
                                    $list[0]['products'][0]['pgms'] = $subProductRow['unit'];
                                }
                            }
                        }
                    }
                }
                array_push($array, $list);
            }
            return $array;
        }else{
            return false;
        }
    }

    public function pendingOrders($rid){
        $rid = $this->conn->real_escape_string($rid);

        $query = "SELECT r.*, t.userid, t.paymentmethod, t.note, t.customaddress, t.customerphone, t.totalproductprice as productsPrice, t.deliverycharge, t.totalprice FROM rnoti r INNER JOIN temporders t on r.oid = t.oid INNER JOIN product p on t.productid = p.id WHERE r.rid = $rid and r.status='pending' order by oid";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $list = [];
                array_push($list, $row);
                $list[0]['products'] = [];
                $query = "SELECT p.pname, p.sname, p.pprice, p.pgms, t.quantity, t.productid, t.subproductid, s.address as shopAddress FROM temporders t INNER JOIN product p on p.id = t.productid INNER JOIN subcategory s on s.id = p.sid where oid=".$row['oid'];
                $orderResult = $this->conn->query($query);
                if($orderResult->num_rows > 0){
                    while($orderRow = $orderResult->fetch_assoc()){
                        array_push($list[0]['products'], $orderRow);
                        if($orderRow['subproductid'] != ""){
                            $subproductQuery = "SELECT * FROM subproduct WHERE id=".$orderRow['subproductid'];
                            $subProductResult = $this->conn->query($subproductQuery);
                            if($subProductResult->num_rows > 0){
                                while($subProductRow = $subProductResult->fetch_assoc()){
                                    $list[0]['products'][0]['pprice'] = $subProductRow['price'];
                                    $list[0]['products'][0]['pgms'] = $subProductRow['unit'];
                                }
                            }
                        }
                    }
                }
                array_push($array, $list);
            }

            return $array;
        }else{
            return false;
        }
    }

    public function readCart($uid){
        $uid = $this->conn->real_escape_string($uid);
        
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

        $query = "SELECT o.oid, o.totalprice as TotalPrice FROM orderid o where o.uid = $uid order by o.id desc LIMIT 7";
        $result = $this->conn->query($query);

        // $query = "SELECT o.*, a.address, p.pname, p.sname, p.cid, p.sid, p.psdesc, p.pgms, p.pprice, p.status, p.stock, p.pimg, p.prel, p.date, p.discount, p.popular FROM $this->table o INNER JOIN product p on p.id = o.productid LEFT JOIN useraddress a ON a.id = o.addressid WHERE o.userid=$uid ORDER BY o.id DESC";
        // $query = "SELECT o.*, t.productid, p.pname FROM orderid o INNER JOIN temporders t on t.oid = o.oid INNER JOIN product p on p.id = t.productid where o.uid=$uid and not o.status=0";
        // $result = $this->conn->query($query);
        $array = [];

        $success = false;

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $list = ["oid"=>"", "TotalPrice"=>"", "DeliveryCharge"=>0, "products"=>[]];
                $list['oid'] = $row['oid'];
                $list['TotalPrice'] = $row['TotalPrice'];
                $query = "SELECT t.*, p.* FROM orderid o INNER JOIN temporders t on t.oid = o.oid INNER JOIN product p on p.id = t.productid where o.uid=$uid and o.oid=".$row['oid'];
                $productResult = $this->conn->query($query);
                if($productResult->num_rows > 0){
                    while($productRow = $productResult->fetch_assoc()){
                        $list['DeliveryCharge'] += $productRow['deliverycharge'];
                        array_push($list['products'], $productRow);
                        if($productRow['subproductid'] != ""){
                            $subproductQuery = "SELECT * FROM subproduct s WHERE s.id = ".$productRow['subproductid'];
                            $subProductResult = $this->conn->query($subproductQuery);
                            if($subProductResult->num_rows > 0){
                                while($subProductRow = $subProductResult->fetch_assoc()){
                                    $list['products'][0]['pprice'] = $subProductRow['price'];
                                    $list['products'][0]['pgms'] = $subProductRow['unit'];
                                }
                            }  
                        }
                    }
                }
                array_push($array, $list);
            }
            $success = true;
        }

        
        $query = "SELECT b.*, p.pname, p.sname, p.cid, p.sid, p.pprice, p.pgms, p.psdesc, p.type producttype, p.pincode, p.popular, p.pimg, p.discount FROM bookings b inner join product p on p.id=b.productid where userid=$uid ORDER BY bookeddate DESC LIMIT 3";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            $success = true;
        }
        if($success === true){
            return $array;
        }else{
            return false;
        }

        
    }

    public function create($userid, $productid, $addressid, $qty, $oid, $phone, $subproducttid, $note="", $address=""){
        $oid = $this->conn->real_escape_string($oid);
        $userid = $this->conn->real_escape_string($userid);
        $productid = $this->conn->real_escape_string($productid);
        $addressid = $this->conn->real_escape_string($addressid);
        $qty = $this->conn->real_escape_string($qty);
        $note = $this->conn->real_escape_string($note);
        $address = $this->conn->real_escape_string($address);
        $phone = $this->conn->real_escape_string($phone);
        $subproducttid = $this->conn->real_escape_string($subproducttid);
        $orderdate = time();

        if($subproducttid == ""){
            $query = "SELECT p.pprice, p.sid, s.deliverycharge from product p inner join subcategory s on s.id=p.sid WHERE p.id=$productid";
        }else{
            $query = "SELECT s.deliverycharge, p.sid, sub.price as pprice from product p inner join subcategory s on s.id=p.sid inner join subproduct sub on sub.id = $subproducttid WHERE p.id=$productid";
        }

        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $deliveryCharge = $row['deliverycharge'];
            $productPrice = $row['pprice'] * $qty;
            $sid = $row['sid'];
        }

        $query = "SELECT t.*, p.sid FROM temporders t inner join product p on p.id=t.productid WHERE t.oid=$oid and p.sid=$sid";
        $result = $this->conn->query($query);
        $rows = $result->num_rows;
        $deliveryCharge = $rows > 0 ? 0 : $deliveryCharge;
        $price = $productPrice + $deliveryCharge;
        if($address == ""){
            $query = "INSERT INTO $this->table SET oid=$oid, sid=$sid, orderdate=$orderdate, userid=$userid, productid=$productid, addressid=$addressid, quantity=$qty, note='$note', customerPhone=$phone, totalprice=$price, totalproductprice=$productPrice, deliverycharge= $deliveryCharge".($subproducttid == "" ? "" : ", subproductid=$subproducttid");
        }else{
            $query = "INSERT INTO $this->table SET oid=$oid, sid=$sid, orderdate=$orderdate, userid=$userid, productid=$productid, addressid=0, quantity=$qty, note='$note', customerPhone=$phone, totalprice=$price, totalproductprice=$productPrice, deliverycharge= $deliveryCharge, customaddress='$address'".($subproducttid == "" ? "" : ", subproductid=$subproducttid");
        }
        $this->conn->query($query);
        if($this->conn->affected_rows > 0){
            $query = "SELECT * FROM orderid where oid=$oid and uid=$userid";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                $query = "SELECT SUM(totalprice) as totalprice from $this->table where oid=$oid";
                $result = $this->conn->query($query);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $totalPrice = $row['totalprice'];
                }
                $query = "UPDATE orderid SET totalprice=$totalPrice WHERE oid=$oid";
                $result = $this->conn->query($query);
                if($this->conn->affected_rows > 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                $query = "SELECT SUM(totalprice) as totalprice from $this->table where oid=$oid";
                $result = $this->conn->query($query);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $totalPrice = $row['totalprice'];
                }
                $query = "INSERT INTO orderid SET oid=$oid, uid=$userid, totalprice=$totalPrice";
                $result = $this->conn->query($query);
                if($this->conn->affected_rows > 0){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }

    }

    public function orders($uid)
    {
        $uid = $this->conn->real_escape_string($uid);
        $query = "SELECT DISTINCT(oid) FROM $this->table WHERE userid=$uid and orderstatus='pending' and deliverystatus='pending'";
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
            $query = "SELECT id FROM `rider` WHERE status=1 and not id = $rid";
        } else {
            $query = "SELECT id FROM `rider` WHERE status=1";
        }
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

    public function deliveryBoyNoti($table, $riderID, $orderID)
    {
        $table = $this->conn->real_escape_string($table);
        $orderID = $this->conn->real_escape_string($orderID);
        $riderID = $this->conn->real_escape_string($riderID);
        
        $query = "SELECT * FROM $table WHERE rid=$riderID and oid=$orderID";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            return true;
        }

        $query = "INSERT INTO $table SET rid=$riderID, oid=$orderID, `date`=NOW()";
        $result = $this->conn->query($query);
        if ($this->conn->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deliveryBoyAccept($oid, $rid)
    {
        $oid = $this->conn->real_escape_string($oid);
        $rid = $this->conn->real_escape_string($rid);

        $query = "SELECT * FROM rnoti where oid=$oid and rid=$rid";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){
            $query = "SELECT * FROM rnoti where oid=$oid and rid=$rid and status='accepted'";
            $result = $this->conn->query($query);
            if($result->num_rows > 0){
                return "Delivery boy already accepted the order";
            }else{
                $query1 = "UPDATE temporders SET deliverystatus='assigned', riderid=$rid where `oid`=$oid and deliverystatus='pending'; ";
                $query1 .= "DELETE FROM rnoti where `status`='pending' and oid=$oid and not rid=$rid; ";
                $query1 .= "UPDATE rnoti SET status='accepted' where rid=$rid and status='pending' and oid=$oid; ";
                $query1 .= "INSERT INTO ordersnoti SET oid=$oid, rid=$rid; ";
                $query1 .= "UPDATE rider SET accept=accept+1 WHERE id=$rid; ";
                $result = $this->conn->multi_query($query1);
                if ($this->conn->affected_rows > 0) {
                    return true;    
                } else {
                    return false;
                }    
            }
        }else{
            return "Delivery boy already declined the order!";
        }

    }

    public function deliveryBoyDecline($oid, $rid)
    {
        $oid = $this->conn->real_escape_string($oid);
        $rid = $this->conn->real_escape_string($rid);

        $query = "DELETE FROM rnoti WHERE rid=$rid and oid=$oid and status='pending'; ";
        $query .= "UPDATE rider SET reject=reject+1 WHERE id=$rid";
        $result = $this->conn->multi_query($query);
        if($this->conn->affected_rows > 0){
            return true;
        }else{
            return false;
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

    public function cancelOrder($oid, $uid){
        $oid = $this->conn->real_escape_string($oid);
        $uid = $this->conn->real_escape_string($uid);

        $query = "UPDATE $this->table SET orderstatus='cancelled', deliverystatus='cancelled' where oid=$oid and userid=$uid";
        $result = $this->conn->query($query);

        if($this->conn->affected_rows > 0){
            $query = "UPDATE orderid SET status=0 where oid=$oid";
            $result = $this->conn->query($query);
            if($this->conn->affected_rows > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
