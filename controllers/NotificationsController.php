<?php

namespace app\controllers;

use app\core\Controller;
use app\models\NotificationsModel;
use app\core\Application;
use Exception;
use app\models\DB;
use ExponentPhpSDK\Expo;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

class NotificationsController extends Controller
{
    private $db;
    private $app;
    private $conn = null;

    public function __construct()
    {
        $this->db = new NotificationsModel();
        $this->app = new Application(dirname(__DIR__));

        $this->conn = new DB();
        $this->conn = $this->conn->conn();

    }
    public function read()
    {
        $json = $this->db->read("noti");
        try {
            if ($json) {
                return $this->render("notifications/notificationList", $json);
            } else {
                return $this->render("notifications/notificationList", "", json_encode(array("No items found!")));
            }
        } catch (Exception $e) {
            http_response_code(400);
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function create()
    {
        if ($this->app->request->getMethod() === "get") {
            return $this->render("notifications/addNotifications");
        } else if ($this->app->request->getMethod() === "post") {
            try {
                if ((isset($_POST['title']) && ($_POST['title'] != "") && isset($_POST['message'])) && ($_POST['message'] != "")) {
                    $result = $this->db->create("noti",$_POST['title'], $_POST["message"]);
                    if ($result) {
                        $msg = urlencode("Added new Notification!");
                        return header("Location: /notifications?msg=$msg");
                    } else {
                        throw new Exception("Unable to add new Notification!");
                    }
                } else {
                    throw new Exception("All input fields are required!");
                }
            } catch (Exception $e) {
                http_response_code(400);
                $msg = urlencode($e->getMessage());
                return header("Location: /notifications/add?msg=$msg");
            }
        }
    }

    public function delete()
    {
        try {
            if (isset($_POST['id'])) {
                if ($this->db->delete("noti", $_POST['id'])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            http_response_code(400);
            $msg = urlencode($e->getMessage());
            return header("Location: /notifications?msg=$msg");
        }
    }

    public function getNotifications()
    {
        try {
            if (isset($_POST['view'])) {
                if ($_POST['view'] != "") {
                    $updateQuery = "UPDATE noti SET is_seen=1 WHERE pushed=1";
                    $this->conn->query($updateQuery);
                    $updateQuery = "UPDATE ordersnoti SET is_seen=1 WHERE is_seen=0";
                    $this->conn->query($updateQuery);
                }
                $query = "SELECT * FROM noti where pushed=1 order by duration desc LIMIT 5";
                $result = $this->conn->query($query);
                $output = "";
                $noti = [];
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        array_push($noti, $row);
                    }
                }

                $query = "SELECT * FROM ordersnoti order by duration desc LIMIT 5";
                $result = $this->conn->query($query);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        array_push($noti, $row);
                    }
                }

                usort($noti, function($date1, $date2){
                    $dateTime1 = strtotime($date1['duration']);
                    $dateTime2 = strtotime($date2['duration']);

                    return $dateTime2 - $dateTime1;
                });

                if (!empty($noti)) {
                    foreach($noti as $n){
                        $output .= "<a href='/". sprintf("%s", isset($n['msg']) ? "notifications" : "orders") ."' class='text-reset notification-item'>
                        <div class='media'>
                            <div class='avatar-xs me-3'>
                                <span class='avatar-title bg-primary rounded-circle font-size-16'>
                                    <i class='bx ". sprintf("%s", isset($n['msg']) ? "bx-bell" : "bx-cart") ."'></i>
                                </span>
                            </div>
                            <div class='media-body'>
                                <h6 class='mt-0 mb-1' key='t-your-order'>" . (isset($n['title']) ? $n['title'] : "Order placed!") . "</h6>
                                <div class='font-size-12 text-muted'>
                                    <p class='mb-1' key='t-grammer'>" . ($n['msg'] ?? sprintf("Order id %s is successfully placed order with rider id %s", $n['oid'], $n['rid'])) . "</p>
                                    <p class='mb-0'><i class='mdi mdi-clock-outline'></i> <span key='t-min-ago'>" . $this->dateTime($n['duration']) . "</span></p>
                                </div>
                            </div>
                        </div>
                    </a>";
                    }
                } else {
                    $output .= "<div class='media'><div class='media-body'><p class='m-2' key='t-grammer'>No Notifications found</p></div></div>";
                }
                $query1 = "SELECT * FROM noti WHERE pushed=1 and is_seen=0";
                $result1 = $this->conn->query($query1);
                $count = $result1->num_rows;

                $query2 = "SELECT * FROM ordersnoti WHERE is_seen=0";
                $result2 = $this->conn->query($query2);
                $count += $result2->num_rows;

                $data = array(
                    "notification" => $output,
                    "unseenNotification" => $count
                );
                return json_encode($data);
            } else {
                throw new Exception("Invalid Request");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function dateTime($time)
    {
        date_default_timezone_set("Asia/kolkata");
        $dTime = substr($time, 0, 10);
        $dTime = explode("-", $dTime);

        $sTime = substr($time, 11);
        $sTime = explode(":", $sTime);

        $year = date("Y") - $dTime[0];
        $month = abs((date("m")) - $dTime[1]);
        $day = abs(date('d') - $dTime[2]);
        $hours = abs(date("H") - $sTime[0]);
        $mins = abs(date("i") - $sTime[1]);
        $sec = abs(date("s") - $sTime[2]);

        $pushedTime = "";

        if ($sec >= 0 && $sec < 60) {
            if ($mins > 0 && $mins < 60) {
                if (($hours > 0 && $hours < 60) || $day >= 1 || $month >= 1 || $year >= 1) {
                    if (($day > 0 && $day < 31) || $month >= 1 || $year >= 1) {
                        if (($month > 0 && $month < 12) || $year >= 1) {
                            if ($year > 0 && $month < 12) {
                                if ($year == 1) {
                                    $pushedTime = "$year year ago";
                                    return $pushedTime;
                                } else {
                                    $pushedTime = "$year years ago";
                                    return $pushedTime;
                                }
                            } else {
                                if ($month == 1) {
                                    $pushedTime = "$month month ago";
                                    return $pushedTime;
                                } else {
                                    $pushedTime = "$month months ago";
                                    return $pushedTime;
                                }
                            }
                        } else {
                            if ($day == 1) {
                                $pushedTime = "$day day ago";
                                return $pushedTime;
                            } else {
                                $pushedTime = "$day days ago";
                                return $pushedTime;
                            }
                        }
                    } else {
                        if ($hours == 1) {
                            $pushedTime = "$hours hour ago";
                            return $pushedTime;
                        } else {
                            $pushedTime = "$hours hours ago";
                            return $pushedTime;
                        }
                    }
                } else {
                    if ($mins == 1) {
                        $pushedTime = "$mins min ago";
                        return $pushedTime;
                    } else {
                        $pushedTime = "$mins mins ago";
                        return $pushedTime;
                    }
                }
            } else {
                $pushedTime = "$sec seconds ago";
                return $pushedTime;
            }
        }
    }

    public function push()
    {
        try {
            if (isset($_POST['id'])) {
                $data = $this->db->push("noti", $_POST['id']);
                if ($data) {
                    $query = "SELECT token, `uid` FROM notitoken";
                    $result = $this->conn->query($query);
                    $noti = true;
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $this->app->expo->subscribe("default", $row['token']);
                        }
                        $noti = $this->expoNotifications($data, $row['token'], $row['uid']);
                    }
                    if($noti === true){
                        $msg = urlencode("Notification pushed!");
                        return header("Location: /notifications?msg=$msg"); 
                    }else{
                        throw new Exception($noti);
                    }
                } else {
                    throw new Exception("Unable to push notification!");
                }
            } else {
                throw new Exception("Invalid Arguments!");
            }
        } catch (Exception $e) {
            $msg = urlencode($e->getMessage());
            http_response_code(400);
            return header("Location: /notifications?msg=$msg");
        }
    }

    public function expoNotifications($data, $token, $uid){
        $channelName = "default";
        $unique = $token;
        $recipant = "$unique";
        $notification = ["title"=>$data[0]['title'], "body"=>$data[0]['msg']];
        $this->app->expo->notify([$channelName], $notification);

        return true;
    }

}
