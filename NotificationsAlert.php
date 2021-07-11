<?php
namespace app;

require __DIR__ . "/vendor/autoload.php";

use app\models\DB;
use app\core\Application;
use Exception;
use app\models\AppModel;


class NotificationsAlert{
    public $conn = null;
    public Application $app;
    public AppModel $appDB;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->app = new Application(dirname(__DIR__));
        $this->appDB = new AppModel();
    }

    public static function alert(){
        (new self)->readTime();
    }

    public function readTime(){
        $query = "SELECT * FROM rnoti where status='pending'";
        $result = $this->conn->query($query);
        $riders = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                date_default_timezone_set("Asia/kolkata");
                $dTime = substr($row['date'], 11, 19);
                $dTime = explode(":", $dTime);
                
                $hours = abs(date("H") - $dTime[0]);
                $mins = abs(date('i') - $dTime[1]);

                if($hours == 0){
                    if($mins >= 4){
                        if(in_array($row["rid"], $riders)){
                            continue;
                        }else{
                            array_push($riders, $row['rid']);
                            $this->notify($row['rid']);    
                        }
                    }else{
                        continue;
                    }
                }else{
                    if(in_array($row["rid"], $riders)){
                        continue;
                    }else{
                        array_push($riders, $row['rid']);
                        $this->notify($row['rid']);    
                    }
                }
            }
        }
    }

    public function checkOrders(){
        $query = "SELECT DISTINCT oid FROM temporders WHERE temporders.oid not in(select oid from rnoti) and deliverystatus='pending' and orderstatus='pending'";
        $result = $this->conn->query($query);
        $array = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }    
            $this->assignDeliveryBoy($array);
        }
    }

    public function assignDeliveryBoy($orders)
    {
        // $channelName = "deliveryboys";
        // $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $riders = $this->getDeliveryBoy();
        // $orders = $this->getOrders($decodedData->data->id);
        if ($orders == true) {
            if ($riders == true) {
                foreach ($orders as $order) {
                    foreach ($riders as $rider) {
                        $result = $this->deliveryBoyNoti($rider['id'], $order['oid']);
                        if ($result) {
                            $query = "SELECT token from rnotitoken WHERE rid=" . $rider['id'] . " LIMIT 1";
                            $result = $this->conn->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // $this->app->expo->subscribe($channelName, $row['token']);
                                    $this->sendNotification(array("title"=>"Place and order!", "msg" => "You have got an order to place!"), "", $row['token']);
                                }
                            }
                        }
                    }
                }
                // $this->expoNotifications(array("title" => "Place an order!", "msg" => "You have got an order to place!"), $channelName);

                return true;
            } else {
                return "No delivery boys available!";
            }
        } else {
            return "No orders available!";
        }
    }

    public function getDeliveryBoy($rid="")
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
            return true;
        } else {
            return false;
        }
    }


    public function notify($rid){
        try{
            $query = "SELECT * FROM rnotitoken where rid=$rid";
            $result = $this->conn->query($query);    
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $this->app->expo->subscribe($rid, $row['token']);
                $noti = $this->sendNotification(array("title"=>"Order Alert!", "msg"=>"You have not responded to the order yet... Act Now!"), $rid, $row['token']);
                if($noti === true){
                    return true;
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function sendNotification($data, $uid, $token){
        // $uid = "deliveryboys";
        // $data = array('title'=>"something", 'msg'=>"something");
        // $token = "ExponentPushToken[GTKmrwLiLQeg0XLX265vbx]";
        $notification = ["title"=>$data['title'], "body"=>$data['msg']];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://exp.host/--/api/v2/push/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query(array("to"=>"$token", "title"=>$data['title'], "body"=>$data['msg'])));


        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $server_output = curl_exec($ch);

        curl_close ($ch);

    }
}

$noti = new NotificationsAlert();
$noti->readTime();
$noti->checkOrders();