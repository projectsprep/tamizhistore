<?php

namespace app;

use app\models\DB;
use app\core\Application;
use Exception;


class NotificationsAlert{
    public $conn = null;
    public Application $app;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->app = new Application(dirname(__DIR__));
    }

    public static function alert(){
        (new self)->readTime();
    }

    public function readTime(){
        $query = "SELECT * FROM rnoti where status='pending'";
        $result = $this->conn->query($query);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                date_default_timezone_set("Asia/kolkata");
                $dTime = substr($row['date'], 11, 19);
                $dTime = explode(":", $dTime);
                
                $hours = abs(date("H") - $dTime[0]);
                $mins = abs(date('i') - $dTime[1]);

                if($hours == 0){
                    if($mins >= 4){
                        $this->notify($row['rid']);
                    }else{
                        continue;
                    }
                }else{
                    $this->notify($row['rid']);
                }
            }
        }
    }

    public function notify($rid){
        try{
            $query = "SELECT * FROM rnotitoken where rid=$rid";
            $result = $this->conn->query($query);    
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $this->app->expo->subscribe($rid, $row['token']);
                $noti = $this->expoNotifications(array("title"=>"Order Alert!", "msg"=>"You have not responded to the order yet... Act Now!"), $rid, $row['token']);
                if($noti === true){
                    return true;
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function expoNotifications($data, $uid, $token){
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
