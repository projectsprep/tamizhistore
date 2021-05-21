<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\DB;

header("Content-type: application/json");

class AppController{
    private $appDB;
    private $conn;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->appDB = new AppModel();
    }
    public function AssignDeliveryBoy($uid='', $rid=''){
        $uid = $uid == "" ? $_POST['uid'] : $uid; 
        $deliveryBoy = json_decode($this->getDeliveryBoy(), true);
        if(isset($rid) && $rid!=""){
            while($deliveryBoy['id'] == $rid){
                $deliveryBoy = json_decode($this->getDeliveryBoy($rid), true);
                if($deliveryBoy == false){
                    return json_encode(array("Result"=>false, "Message"=>"Delivery boys are not available!"));
                }
            }
        }
        $orders = json_decode($this->getOrders($uid), true);
        if($orders != NULL && count($orders) >= 1){
            if($deliveryBoy != NULL && count($deliveryBoy) >= 1){
                $success = false;
                foreach($orders as $order){
                    $query = "UPDATE orders set rid=".$deliveryBoy['id'].", r_status='pending' where oid = '".$order['oid']."'";
                    $result = $this->conn->query($query);
                    if($this->conn->affected_rows > 0){
                        if($this->deliveryBoyNoti($deliveryBoy['id'], $order['oid'])){
                            $success = true;
                        }else{
                            return json_encode(array("Result"=>false, "Message"=>"Unable to place order!"));
                        }  
                    }else{
                        return json_encode(array("Result"=>false, "Message"=>"Unable to place order!"));
                    }
                }
                if($success == true){
                    return json_encode(array("Result"=>true, "Message"=>"Order placed successfully with rider id ".$deliveryBoy['id']));
                }
            }else{
                return json_encode(array("Result"=>false, "Message"=>"Delivery boys are not available!"));
            }
        }else{
            return json_encode(array("Result"=>false, "Message"=>"No orders found with the user id!"));
        }
    }

    public function getOrders($uid){
        $orders = $this->appDB->orders("orders", $uid);
        if($orders){
            return $orders;
        }else{
            return false;
        }
    }

    public function getDeliveryBoy($rid=""){
        $deliveryBoy = $this->appDB->deliveryBoy("rider", $rid);
        if($deliveryBoy){
            return $deliveryBoy;
        }else{
            return false;
        }
    }

    public function deliveryBoyNoti($riderID, $orderID){
        $noti = $this->appDB->deliveryBoyNoti("rnoti", $riderID, $orderID);
        if($noti){
            return json_encode(array("Result"=>true, "Message"=>"Notification sent to rider id $riderID"));
        }else{
            return json_encode(array("Result"=>false, "Message"=>"Unable to send notification to rider if $riderID!"));
        }
    }

    public function deliveryBoyNotiRes(){
        if(isset($_POST['res']) && isset($_POST['rid']) && isset($_POST['uid'])){
            if($_POST['res'] == 1){
                //set r_status as assigned in orders
                // set status as accepted in rnoti
                // set is_available as 0 in rider
                $result = $this->appDB->deliveryBoyAccept($_POST['uid'], $_POST['rid']);
                if($result){
                    return json_encode(array("Result"=>true, "Message"=>"Delivery boy with id " . $_POST['rid'] . " accepted the order!"));

                }else{
                    return json_encode(array("Result"=>false, "Message"=>"Unable to assign delivery boy!"));
                }

            }else if($_POST['res'] == 0){
                // set r_status as not assigned and rid = 0 in orders table
                // set status as declined in rnoti
                // re-assign rider
                $result = $this->appDB->deliveryBoyDecline($_POST['uid'], $_POST['rid']);
                if($result){
                    echo json_encode(array("Result"=>false, "Message"=>"Delivery boy with id " . $_POST['rid'] . " declined the order!"));
                    return $this->AssignDeliveryBoy($_POST['uid'], $_POST['rid']);
                }else{
                    return json_encode(array("Result"=>false, "Message"=>"Unable to assign delivery boy!"));
                }
            }
        }else{
            return json_encode(array("Result"=>false, "Message"=>"Request did not match our needs!"));
        }
    }

    public function assignedOrders(){
        return $this->appDB->assignedOrders();
    }
}