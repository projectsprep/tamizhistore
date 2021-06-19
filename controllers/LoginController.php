<?php

namespace App\controllers;


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

use app\core\Controller;
use app\models\LoginModel;
use \Firebase\JWT\JWT;

session_start();

class LoginController extends Controller{
    private $db;

    public function __construct()
    {
        $this->db = new LoginModel();
    }

    public function login(){
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->username) && !empty($data->phone) && !empty($data->password) && !isset($data->name)){
            $result = $this->db->login($data->username, $data->phone, $data->password);
            if(gettype($result) == "array"){
                $iss = "tamizhistore";
                $iat = time();
                $nbf = $iat;
                $aud = "users";

                $secretKey = "tamizhiowt";

                $payloadInfo = array(
                    "iss"=>$iss,
                    "iat"=>$iat,
                    "nbf"=>$nbf,
                    "aud"=>$aud,
                    "data"=>$result
                );
                $jwt = JWT::encode($payloadInfo, $secretKey, 'HS256');
                return json_encode(array("result"=>true, "token"=>$jwt));
            }else if(gettype($result) == "string"){
                http_response_code(401);
                return json_encode(array("result"=>false, "message"=>$result));
            }
        }else if(!empty($data->username) && !empty($data->phone) && !empty($data->password) && !empty($data->name)){
            if(($data->username != "") && ($data->phone != "") && ($data->password != "") && ($data->name != "")){
                return $this->create($data->username, $data->phone, $data->password, $data->name);
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Invalid arguments"));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"Invalid arguments"));
        }
    }

    public function create($username, $phone, $password, $name){
        $result = $this->db->create($username, $password, $phone, $name);
        if($result){
            $iss = "tamizhistore";
            $iat = time();
            $nbf = $iat;
            $aud = "users";

            $secretKey = "tamizhiowt";

            $payloadInfo = array(
                "iss"=>$iss,
                "iat"=>$iat,
                "nbf"=>$nbf,
                "aud"=>$aud,
                "data"=>$result
            );
            $jwt = JWT::encode($payloadInfo, $secretKey, 'HS256');
            return json_encode(array("result"=>true, "token"=>$jwt));
        }else{
            http_response_code(401);
            return json_encode(array("result"=>false, "message"=>"Unable to create a new account!"));
        }
    }
}