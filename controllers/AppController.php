<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\DB;
use Exception;
use \Firebase\JWT\JWT;
use app\core\Application;

use function PHPSTORM_META\type;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


class AppController
{
    protected $appDB;
    protected $conn;
    protected $secretKey = "tamizhiowt";
    protected $token;
    protected $table = "temporders";
    protected $riderID;
    public Application $app;

    public function __construct()
    {
        $this->conn = new DB();
        $this->conn = $this->conn->conn();
        $this->appDB = new AppModel();
        $this->app = new Application(dirname(__DIR__));

        $header = getallheaders();

        if (isset($header['Authorization']) && ($header['Authorization'] != "")) {
            try {
                $secretKey = "tamizhiowt";

                $this->token = $header['Authorization'];
                $decodedData = JWT::decode($this->token, $secretKey, array("HS256"));
            } catch (Exception $e) {
                http_response_code(403);
                echo json_encode(array("result" => false, "message" => $e->getMessage()));
                exit();
            }
        } else {
            http_response_code(401);
            echo json_encode(array("result" => false, "message" => "User not Authorized"));
            exit();
        }
    }

    public function pendingOrders()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->appDB->pendingOrders($decodedData->data->id);
        if ($result) {
            return json_encode(array("result" => true, "data" => $result));
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "No orders to place!"));
        }
    }

    public function cartOrders()
    {
        // to get the data
        $data = json_decode(file_get_contents("php://input"));

        // optional values
        $address = isset($data->address) && $data->address != "" ? $data->address : "";
        $note = isset($data->note) && $data->note != "" ? $data->note : "";

        if (!isset($data->phone) && ($data->phone == "")) {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }

        $phone = $data->phone;
        if (strlen($phone) > 10 || strlen($phone) < 10) {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Phone should be a valid 10 digit number!"));
        }

        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));

        //to get products from cart
        $result = $this->appDB->readCart($decodedData->data->id);
        $success = true;
        if ($result) {
            // initialize and order id to the orders, reassign them if already exist
            $oid = rand(100000, 10000000);
            $query = "SELECT oid FROM temporders where oid=$oid";
            $res = $this->conn->query($query);
            if ($res->num_rows > 0) {
                $oid = rand(100000, 10000000);
            }

            // initialize orders
            foreach ($result as $row) {
                $result = $this->appDB->create($decodedData->data->id, $row['productid'], $row['aid'], $row['quantity'], $note, $address, $oid, $phone);
                if ($result === true) {
                    // if order is made, delete products from cart
                    $query = "DELETE FROM cart WHERE id=" . $row['id'];
                    $this->conn->query($query);
                    if ($this->conn->affected_rows > 0) {
                        $success = true;
                        // return json_encode(array("result"=>true));
                    } else {
                        http_response_code(400);
                        return json_encode(array("result" => false, "message" => "Something went wrong in placing the order"));
                    }
                } else {
                    http_response_code(400);
                    return json_encode($result);
                }
            }

            if ($success) {
                $this->assignDeliveryBoy();
                return json_encode(array("result" => true));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "No orders found in cart!"));
        }
    }

    public function makeOrder($oid, $pid = "", $qty = "", $aid = "", $note = "", $address = "", $phone)
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));

        // create an order in the database
        $result = $this->appDB->create($decodedData->data->id, $pid, $aid, $qty, $note, $address, $oid, $phone);
        if ($result) {
            $result = $this->assignDeliveryBoy();
            if ($result === true) {
                return true;
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }

    public function cancelOrder()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->oid) && ($data->oid != "")) {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $result = $this->appDB->cancelOrder($data->oid, $decodedData->data->id);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to cancel order!"));
            }
        }
    }

    public function assignDeliveryBoy()
    {
        $channelName = "deliveryboys";
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $riders = $this->getDeliveryBoy();
        $orders = $this->getOrders($decodedData->data->id);
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
                                    $this->app->expo->subscribe($channelName, $row['token']);
                                }
                            }
                        }
                    }
                }
                $this->expoNotifications(array("title" => "Place an order!", "msg" => "You have got an order to place!"), $channelName);

                return true;
            } else {
                return "No delivery boys available!";
            }
        } else {
            return "No orders available!";
        }
    }

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
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        if ($noti) {
            return true;
        } else {
            return false;
        }
    }

    public function deliveryBoyNotiRes()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->res) && ($data->res !== "") && isset($data->oid) && ($data->oid !== "")) {
            if ($data->res == 1) {
                $result = $this->appDB->deliveryBoyAccept($data->oid, $decodedData->data->id);
                if ($result === true) {
                    $query = "SELECT o.uid, n.token from orderid o inner join notitoken n on n.uid = o.uid where oid=$data->oid LIMIT 1";
                    $result = $this->conn->query($query);
                    $uid = "";
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $uid = $row['uid'];
                        $this->app->expo->subscribe($row['uid'], $row['token']);
                        $this->expoNotifications(array("title" => 'Order Info', "msg" => "Your order is placed successfully. Thank you for your orders!"), $uid);
                    }
                    return json_encode(array("result" => true));
                } else if ($result == true) {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => $result));
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "Unable to assign delivery boy!"));
                }
            } else if ($data->res == 0) {
                $result = $this->appDB->deliveryBoyDecline($data->oid, $decodedData->data->id);
                if ($result === true) {
                    return json_encode(array("result" => true));
                } else if ($result === false) {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "Unable to decline the order!"));
                }
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function assignedOrders()
    {
        return $this->appDB->assignedOrders();
    }

    public function userOrders()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->appDB->read($decodedData->data->id);
        if ($result) {
            return json_encode(array("result" => true, "data" => $result, "isMore" => false));
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "No orders found!"));
        }
    }


    public function expoNotifications($data, $uid)
    {
        $channelName = "default";
        $notification = ["title" => $data['title'], "body" => $data['msg']];
        $this->app->expo->notify([$uid], $notification);
    }
}
