<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\DB;
use Exception;
use \Firebase\JWT\JWT;

use function PHPSTORM_META\type;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


class AppController
{
    private $appDB;
    private $conn;
    private $secretKey = "tamizhiowt";
    private $token;
    private $table = "temporders";
    private $riderID;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->appDB = new AppModel();

        $header = getallheaders();

        if(isset($header['Authorization']) && ($header['Authorization'] != "")){
            try{
                $secretKey = "tamizhiowt";
        
                $this->token = $header['Authorization'];
                $decodedData = JWT::decode($this->token, $secretKey, array("HS256"));
            }catch(Exception $e){
                http_response_code(403);
                echo json_encode(array("result"=>false, "message"=>$e->getMessage()));
                exit();
            }
        }else{
            http_response_code(401);
            echo json_encode(array("result"=>false, "message"=>"User not Authorized"));
            exit();
        }
    }
    

    public function makeOrder($oid, $pid="", $qty="", $aid="", $note="", $address=""){
        $data = json_decode(file_get_contents("php://input"));
        if(!(isset($pid) && $pid!="") && !(isset($qty) && $qty!="") && !(isset($aid) && $aid!="")){
            if(isset($data->pid) && isset($data->qty) && isset($data->aid)){
                if(($data->pid!="") && ($data->qty!="") && ($data->aid!="")){
                    $pid = $data->pid;
                    $qty = $data->qty;
                    $aid = $data->aid;
                }else{
                    http_response_code(400);
                    return json_encode(array("result"=>false, "message"=>"Invalid arguments"));
                }
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Invalid arguments"));
            }    
        }
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->appDB->create($decodedData->data->id, $pid, $aid, $qty, $note, $address, $oid);
        if($result){
            return $this->assignDeliveryBoy("", "", $this->riderID['id']);
            // if($result == true){
            //     return json_encode(array("result"=>true));
            // }else{
            //     http_response_code(400);
            //     return json_encode(array("result"=>false, "message"=>"Unable to assign delivery boy!"));
            // }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"Unable to make order!"));
        }
    }

    public function assignDeliveryBoy($uid="", $rid = "", $manualId=""){
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        
        $uid = $uid == "" ? $decodedData->data->id : $uid;
        $deliveryBoy = $manualId == "" ? $this->getDeliveryBoy() : $manualId;
        if (isset($rid) && $rid != "") {
            if(type($deliveryBoy) == "number"){
                while ($deliveryBoy == $rid) {
                    $deliveryBoy = $this->getDeliveryBoy($rid);
                    if ($deliveryBoy == false) {
                        return json_encode(array("Result" => false, "Message" => "Delivery boys are not available!"));
                    }
                }    
            }else{
                while ($deliveryBoy['id'] == $rid) {
                    $deliveryBoy = $this->getDeliveryBoy($rid);
                    if ($deliveryBoy == false) {
                        return json_encode(array("Result" => false, "Message" => "Delivery boys are not available!"));
                    }
                }    
            }
        }

        $orders = $this->getOrders($uid);
        if ($orders != NULL && count($orders) >= 1) {
            if ($deliveryBoy != NULL) {
                $success = false;
                foreach($orders as $order){
                    $deliveryBoy = $manualId == "" ? $deliveryBoy['id'] : $manualId;
                    $order = $order['id'];
                    $query = "UPDATE $this->table SET riderid=$deliveryBoy, deliverystatus='assigned' WHERE id=$order";
                    $result = $this->conn->query($query);
                    if($this->conn->affected_rows > 0){
                        $success = true;
                    }else{
                        http_response_code(400);
                        return json_encode(array("result"=>false, "message"=>"Unable to place order!"));            
                    }
                }
                if($success == true){
                    return json_encode(array("result"=>true));
                }else{
                    http_response_code(400);
                    return json_encode(array("result"=>false, "message"=>"Unable to place order!"));
                }
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Delivery boys are not available!"));    
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No orders found with the user!"));
        }
    }

    // public function AssignDeliveryBoy($uid = '', $rid = '')
    // {
    //     $uid = $uid == "" ? $_POST['uid'] : $uid;
        // $deliveryBoy = json_decode($this->getDeliveryBoy(), true);
    //     if (isset($rid) && $rid != "") {
    //         while ($deliveryBoy['id'] == $rid) {
    //             $deliveryBoy = json_decode($this->getDeliveryBoy($rid), true);
    //             if ($deliveryBoy == false) {
    //                 return json_encode(array("Result" => false, "Message" => "Delivery boys are not available!"));
    //             }
    //         }
    //     }
    //     $orders = json_decode($this->getOrders($uid), true);
    //     if ($orders != NULL && count($orders) >= 1) {
    //         if ($deliveryBoy != NULL && count($deliveryBoy) >= 1) {
    //             $success = false;
    //             foreach ($orders as $order) {
                    // $query = "UPDATE orders set rid=" . $deliveryBoy['id'] . ", r_status='pending' where oid = '" . $order['oid'] . "'";
    //                 $result = $this->conn->query($query);
    //                 if ($this->conn->affected_rows > 0) {
    //                     if ($this->deliveryBoyNoti($deliveryBoy['id'], $order['oid'])) {
    //                         $success = true;
    //                     } else {
    //                         return json_encode(array("Result" => false, "Message" => "Unable to place order!"));
    //                     }
    //                 } else {
    //                     return json_encode(array("Result" => false, "Message" => "Unable to place order!"));
    //                 }
    //             }
    //             if ($success == true) {
    //                 return json_encode(array("Result" => true, "Message" => "Order placed successfully with rider id " . $deliveryBoy['id']));
    //             }
    //         } else {
    //             return json_encode(array("Result" => false, "Message" => "Delivery boys are not available!"));
    //         }
    //     } else {
    //         return json_encode(array("Result" => false, "Message" => "No orders found with the user id!"));
    //     }
    // }

    public function getOrders($uid)
    {
        $orders = $this->appDB->orders($uid);
        if ($orders) {
            return $orders;
        } else {
            return false;
        }
    }

    public function getDeliveryBoy($rid = "")
    {
        $deliveryBoy = $this->appDB->deliveryBoy("rider", $rid);
        if ($deliveryBoy) {
            return $deliveryBoy;
        } else {
            return false;
        }
    }

    public function deliveryBoyNoti($riderID, $orderID)
    {
        $noti = $this->appDB->deliveryBoyNoti("rnoti", $riderID, $orderID);
        if ($noti) {
            return json_encode(array("Result" => true, "Message" => "Notification sent to rider id $riderID"));
        } else {
            return json_encode(array("Result" => false, "Message" => "Unable to send notification to rider if $riderID!"));
        }
    }

    public function deliveryBoyNotiRes()
    {
        if (isset($_POST['res']) && isset($_POST['rid']) && isset($_POST['uid'])) {
            if ($_POST['res'] == 1) {
                //set r_status as assigned in orders
                // set status as accepted in rnoti
                // set is_available as 0 in rider
                $result = $this->appDB->deliveryBoyAccept($_POST['uid'], $_POST['rid']);
                if ($result === true) {
                    return json_encode(array("Result" => true, "Message" => "Delivery boy with id " . $_POST['rid'] . " accepted the order!"));
                } else if ($result === false) {
                    return json_encode(array("Result" => false, "Message" => "Unable to assign delivery boy!"));
                }
            } else if ($_POST['res'] == 0) {
                // set r_status as not assigned and rid = 0 in orders table
                // set status as declined in rnoti
                // re-assign rider
                $result = $this->appDB->deliveryBoyDecline($_POST['uid'], $_POST['rid']);
                if ($result === true) {
                    echo json_encode(array("Result" => false, "Message" => "Delivery boy with id " . $_POST['rid'] . " declined the order!"));
                    return $this->AssignDeliveryBoy($_POST['uid'], $_POST['rid']);
                } else if ($result === false) {
                    return json_encode(array("Result" => false, "Message" => "Unable to assign delivery boy!"));
                }
            }
        } else {
            return json_encode(array("Result" => false, "Message" => "Request did not match our needs!"));
        }
    }

    public function assignedOrders()
    {
        return $this->appDB->assignedOrders();
    }

    public function userOrders(){
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->appDB->read($decodedData->data->id);
        if($result){
            return json_encode(array("result"=>true, "data"=>$result, "isMore"=> false));
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No orders found!"));
        }
    }

    public function cartOrders(){
        $data = json_decode(file_get_contents("php://input"));
        $address = isset($data->address) && $data->address != "" ? $data->address : "";
        $note = isset($data->note) && $data->note!= "" ? $data->note : "";
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->appDB->readCart($decodedData->data->id);
        $success = true;
        if($result){
            $oid = rand(100000, 1000000);
            $this->riderID = $this->getDeliveryBoy();
            foreach($result as $row){
                $result = $this->makeOrder($oid, $row['productid'], $row['quantity'], $row['aid'], $note, $address);
                $result = json_decode($result);
                if($result->result == true){
                    $query = "DELETE FROM cart WHERE id=".$row['id'];
                    $this->conn->query($query);
                    if($this->conn->affected_rows > 0){
                        $success = true;
                    }else{
                        http_response_code(400);
                        return json_encode(array("result"=>false, "message"=>"Something went wrong in placing the order"));            
                    }
                }else{
                    http_response_code(400);
                    return json_encode(array("result"=>false, "message"=>"Unable to place order!"));        
                }
            }

            if($success){
                return json_encode(array("result"=>true));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No orders found in cart!"));
        }
    }
}
