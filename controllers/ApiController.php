<?php

namespace app\controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

use app\core\Controller;
use app\core\Application;
use app\models\ProductsModel;
use app\models\CategoryModel;
use app\models\NotificationsModel;
use app\models\DB;
use app\models\CouponModel;
use app\models\AreaModel;
use app\models\TimeSlotsModel;
use app\models\PaymentModel;
use app\models\CountryCodeModel;
use app\models\SubCategoryModel;
use app\models\CartModel;

use \Firebase\JWT\JWT;


use Exception;

// session_start();
// // if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
// //     header("Location: /login");
// // }

class ApiController extends Controller
{
    private DB $db;
    private ProductsModel $pDB;
    private CategoryModel $cDB;
    private NotificationsModel $nDB;
    private CouponModel $couponDB;
    private AreaModel $areaDB;
    private TimeSlotsModel $tsDB;
    private PaymentModel $paymentDB;
    private CountryCodeModel $codeDB;
    private SubCategoryModel $sDB;
    private Application $app;
    private CartModel $cartDB;

    public function __construct()
    {
        $this->db = new DB();
        $this->conn = $this->db->conn();
        $this->pDB = new ProductsModel();
        $this->cDB = new CategoryModel();
        $this->nDB = new NotificationsModel();
        $this->couponDB = new CouponModel();
        $this->areaDB = new AreaModel();
        $this->tsDB = new TimeSlotsModel();
        $this->paymentDB = new PaymentModel();
        $this->codeDB = new CountryCodeModel();
        $this->sDB = new SubCategoryModel();
        $this->cartDB = new CartModel();
        $this->app = new Application(dirname(__DIR__));

        $headers = getallheaders();

        if(isset($headers['Authorization']) && ($headers['Authorization'] != "")){
            try{
                $secretKey = "tamizhiowt";
        
                $token = $headers['Authorization'];
                $decodedData = JWT::decode($token, $secretKey, array("HS256"));
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

    public function cartInc(){
        $data = json_decode(file_get_contents("php://input"));
        if((isset($data->id)) && ($data->id != "")){
            $result = $this->cartDB->increment($data->id);
            if($result == true){
                return json_encode(array("result"=>true));
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Unable to update the data!"));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"Invalid arguments!"));
        }
    }

    public function cartDec(){
        $data = json_decode(file_get_contents("php://input"));
        if((isset($data->id)) && ($data->id != "")){
            $result = $this->cartDB->decrement($data->id);
            if($result == true){
                return json_encode(array("result"=>true));
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Unable to update the data!"));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"Invalid arguments!"));
        }
    }

    public function removeCart(){
        $data = json_decode(file_get_contents("php://input"));
        if(isset($data->id) && ($data->id != "")){
            $result = $this->cartDB->delete($data->id);
            if($result == true){
                return json_encode(array("result"=>true));
            }else{
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Unable to delete product from cart"));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"Invalid arguments!"));
        }
    }

    public function cart(){
        if($this->app->request->getMethod() == "get"){
            if(isset($_GET['uid']) && ($_GET['uid'] != "")){
                $json = $this->cartDB->read($_GET['uid']);
                if($json){
                    return json_encode(["searchResult"=>true, "data"=>$json, "isMore"=>false]);
                }else{
                    return json_encode(array("searchResult"=>false, "message"=>"No products found!"));
                }
            }else{
                http_response_code(400);
                return json_encode(array('result'=>false, "message"=>"Invalid arguments"));
            }
        }else if($this->app->request->getMethod() == "post"){
            $data = json_decode(file_get_contents("php://input"));
            if(isset($data->uid) && isset($data->pid) && ($data->uid != "") && ($data->pid != "")){
                $result = $this->cartDB->add($data->uid, $data->pid);
                if($result == true){
                    return json_encode(array("result"=>true));
                }else{
                    http_response_code(400);
                    return json_encode(array("result"=>false, "message"=>"Unable to add to cart!"));
                }
            }else{
                http_response_code(400);
                return json_encode(array('result'=>false, "message"=>"Invalid arguments"));
            }
        }
    }

    public function expoNotifications(){
        $channelName = "default";
        try{
            if(isset($_POST['token']) && ($_POST['token'] != "")){
                $unique = $_POST['token'];
                $recipant = "ExponentPushToken[$unique]";
                $notification = ['body' => 'This is a noti from php expo script', 'data'=> json_encode(array('someData' => 'goes here'))];
                $this->app->expo->subscribe($channelName, $recipant);
                $this->app->expo->notify([$channelName], $notification);
            }else{
                throw new Exception("Invalid token");
            }
        }catch(Exception $e){
            return json_encode($e->getMessage());
        }
    }

    public function getBanner(){
        $query = "SELECT * FROM banner";
        $result = $this->conn->query($query);
        $array = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($array, $row);
            }
            return json_encode($array);
        }else{
            http_response_code(400);
            return json_encode(array("searchResult"=>false, "message"=>"No Results found!"));
        }
    }

    public function getRandomCategories(){
        try{
            if(isset($_GET['rows']) && ($_GET['rows']!="")){
                $json = $this->cDB->read("category");
                $array = json_decode($json);
                shuffle($array);
                $array = array_slice($array, 0, $_GET['rows']);
                $json = json_encode($array);
                return $json;
            }else{
                throw new Exception("Not enough argument found!");
            }
        }catch(Exception $e){
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getSearchProduct(){
        if((isset($_GET['query'])) && ($_GET['query'] != "")){
            $json = $this->pDB->getSearchProduct("product", $_GET['query']);
            if($json){
                return json_encode(["searchResult"=>true, "data"=>$json, "isMore"=>false]);
            }else{
                http_response_code(400);
                return json_encode(["searchResult"=>false, "message"=>"No products found!"]);
            }
        }else{
            http_response_code(400);
            return json_encode("Invalid arguments!");
        }
    }

    public function getRandomSubCategories(){
        try{
            if(isset($_GET['rows']) && ($_GET['rows']!="")){
                $json = $this->sDB->read("subcategory");
                $array = json_decode($json);
                shuffle($array);
                $array = array_slice($array, 0, $_GET['rows']);
                $json = json_encode($array);
                return $json;
            }else{
                throw new Exception("Not enough argument found!");
            }
        }catch(Exception $e){
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getSubCategories()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->sDB->getSubCategoryById("subcategory", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID!");
                }
            } else {
                return $this->sDB->read("subcategory");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getCodelist()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->codeDB->getCodeById("code", $_POST['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->codeDB->read("code");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getProductWithList()
    {
        $query = "select p.id, p.pname, p.pimg, p.sname, category.catname, subcategory.name, p.pgms, p.pprice, p.stock, p.status from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc limit 10";
        $result = $this->conn->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                $output .= "<tr>
                <td>
                    <span>
                        " . $i++ . "
                    </span>
                </td>
                <td>
                    <h5 class='font-size-14 mb-1'>" . $row['pname'] . "</h5>
                </td>
                <td>
                        <img src='" . $row['pimg'] . "' class='img-thumbnail rounded' alt=''>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . $row['sname'] . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . $row['catname'] . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . $row['name'] . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . $row['pprice'] . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . $row['pgms'] . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . ($row['stock'] ? 'Yes' : 'No') . "</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>" . ($row['status'] ? 'Published' : 'Unpublished') . "</h5>
                    </div>
                </td>
                <td>
                    <a href='/productlist/delete?id=" . $row['id'] . "' class='text-danger'><i class='bx bx-trash-alt'></i></a>
                    <a href='/productlist/edit?id=<?=" . $row['id'] . "'><i class='bx bx-edit'></i></a>
                </td>
                
            </tr>";
            }
        } else {
            $output .= "No results found";
        }
        $this->conn->close();
        return json_encode($output);
    }

    public function getTimeslot()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->tsDB->getTimeslotById("timeslot", $_POST['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->tsDB->read("timeslot");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getArea()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->areaDB->getAreaById("area_db", $_POST['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->areaDB->read("area_db");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getCoupons()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->couponDB->getCouponById("tbl_coupon", $_POST['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->couponDB->read("tbl_coupon");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getPayment()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->paymentDB->getPaymentById("payment_list", $_POST['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->paymentDB->read("payment_list");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getCategoryWithList()
    {
        $query = "SELECT * FROM category ORDER BY id DESC";
        $result = $this->conn->query($query);
        $output = "";
        if ($result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                $output .= "<tr>
                            <td>
                                <span>" .
                    $i++
                    . "</span>
                            </td>
                            <td>
                                <h5 class='font-size-14 mb-1'>" . $row['catname'] . "</h5>
                            </td>
                            <td>
                                <img src='" . $row['catimg'] . "' class='img-thumbnail rounded' alt=''>
                            </td>
                            <td>
                                <div>
                                    <h5 class='font-size-14 mb-1'>" . $this->conn->query('select * from subcategory where cat_id = ' . $row['id'] . ';')->num_rows . "</h5>
                                </div>
                            </td>
                            <td>
                                <a href='/categorylist/delete?id=" . $row['id'] . "'class='text-danger'><i class='bx bx-trash-alt'></i></a>
                                <a href='#'><i class='bx bx-edit editcategory' id=" . $row['id'] . "></i></a>
                            </td>
                        </tr>
                ";
            }
        } else {
            $output .= "No results found";
        }
        $this->conn->close();
        return json_encode($output);
    }

    public function getSubcategoryNames()
    {
        if (isset($_POST['id'])) {
            return $this->pDB->getSubcategoryNames("subcategory", $_POST['id']);
        } else {
            http_response_code(400);
            return json_encode(array("Message" => "Invalid ID"));
        }
    }

    public function getProducts()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    return $this->pDB->getProductById("product", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->pDB->read('product');
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getFoodItems(){
        return $this->pDB->getFoodItems();
    }

    public function getRandomFoodItems(){
        try{
            if(isset($_GET['rows']) && ($_GET['rows']!="")){
                $json = $this->pDB->getFoodItems();
                $array = json_decode($json);
                shuffle($array);
                $array = array_slice($array, 0, $_GET['rows']);
                $json = json_encode($array);
                return $json;
            }else{
                throw new Exception("Not enough arguments found");
            }
        }catch(Exception $e){
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getRandomProducts(){
        try{
            if(isset($_POST['rows']) && $_POST['rows']!=""){
                $json = $this->pDB->read('product');
                $array = json_decode($json);
                shuffle($array);
                $array = array_slice($array, 0, $_POST['rows']);
                $json = json_encode($array);
                return $json;
            }else{
                throw new Exception("Not enough arguments found");
            }
        }catch(Exception $e){
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getCategories()
    {
        try {
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== '') {
                    return $this->cDB->getCategoryById("category", $_GET['id']);
                } else {
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->cDB->read("category");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getCategoryNamesWithList()
    {
        return $this->pDB->getCategoryNames("category");
    }

    public function dateTime($time)
    {
        date_default_timezone_set("Asia/kolkata");
        $dTime = substr($time, 0, 10);
        $dTime = explode("-", $dTime);

        $sTime = substr($time, 11);
        $sTime = explode(":", $sTime);

        // var d = new Date();
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

    public function getNotifications()
    {
        try {
            if (isset($_POST['view'])) {
                if ($_POST['view'] != "") {
                    $updateQuery = "UPDATE noti SET is_seen=1 WHERE pushed=1";
                    $this->conn->query($updateQuery);
                }
                $query = "SELECT * FROM noti where pushed=1 order by duration desc LIMIT 5";
                $result = $this->conn->query($query);
                $output = "";

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $output .= "<a href='#' class='text-reset notification-item'>
                        <div class='media'>
                            <div class='avatar-xs me-3'>
                                <span class='avatar-title bg-primary rounded-circle font-size-16'>
                                    <i class='bx bx-cart'></i>
                                </span>
                            </div>
                            <div class='media-body'>
                                <h6 class='mt-0 mb-1' key='t-your-order'>" . $row['title'] . "</h6>
                                <div class='font-size-12 text-muted'>
                                    <p class='mb-1' key='t-grammer'>" . $row['msg'] . "</p>
                                    <p class='mb-0'><i class='mdi mdi-clock-outline'></i> <span key='t-min-ago'>" . $this->dateTime($row['duration']) . "</span></p>
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

    public function pushedNotifications()
    {
        return $this->nDB->pushedNotifies("noti");
    }
}
