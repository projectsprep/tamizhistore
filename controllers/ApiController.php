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
use app\models\SubCategoryModel;
use app\models\CartModel;
use app\models\UserAddressModel;
use app\models\LoginModel;
use app\models\RatingModel;
use app\models\NotiTokenModel;
use app\models\ApiProductsModel;
use app\models\CustomersModel;
use app\models\BookingsModel;
use app\models\DelNotiTokenModel;
use app\models\StepsInfoModel;

use \Firebase\JWT\JWT;


use Exception;

class ApiController extends Controller
{
    private DB $db;
    private ProductsModel $pDB;
    private CategoryModel $cDB;
    private NotificationsModel $nDB;
    private SubCategoryModel $sDB;
    private Application $app;
    private CartModel $cartDB;
    private UserAddressModel $addressDB;
    private LoginModel $loginDB;
    private static RatingModel $ratingDB;
    private NotiTokenModel $notiTokenDB;
    private ApiProductsModel $apDB;
    private CustomersModel $customerDB;
    private BookingsModel $bookingDB;
    private DelNotiTokenModel $delnotiDB;
    private StepsInfoModel $bannerInfoDB;
    private $secretKey = "tamizhiowt";
    private $token;
    private $conn = null;
    public static $decodedData;
    private $imageDest;

    private $pincode = "614713";

    public function __construct()
    {
        $this->db = new DB();
        $this->conn = $this->db->conn();
        $this->pDB = new ProductsModel();
        $this->cDB = new CategoryModel();
        $this->nDB = new NotificationsModel();
        $this->sDB = new SubCategoryModel();
        $this->cartDB = new CartModel();
        $this->addressDB = new UserAddressModel();
        $this->loginDB = new LoginModel();
        self::$ratingDB = new RatingModel();
        $this->notiTokenDB = new NotiTokenModel();
        $this->apDB = new ApiProductsModel();
        $this->customerDB = new CustomersModel();
        $this->bookingDB = new BookingsModel();
        $this->delnotiDB = new DelNotiTokenModel();
        $this->bannerInfoDB = new StepsInfoModel();
        $this->app = new Application(dirname(__DIR__));

        $headers = getallheaders();

        if (isset($headers["Pincode"]) && ($headers['Pincode'] !== "")) {
            $this->pincode = $headers['Pincode'];
        }

        if (isset($headers['Authorization']) && ($headers['Authorization'] != "")) {
            try {
                $secretKey = "tamizhiowt";

                $this->token = $headers['Authorization'];
                self::$decodedData = JWT::decode($this->token, $secretKey, array("HS256"));
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

    public function test(){
        $query = "SELECT * FROM testing";
        $result = $this->conn->query($query);
        if($result->num_rows > 0){  
            $row = $result->fetch_assoc();
            if($row['active'] == 1){
                return json_encode(array("result"=>true));
            }else if($row['active'] == 0){
                return json_encode(array("result"=>false, "title"=>$row['title'], "message"=>$row['message']));
            }
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No testings found!"));
        }
    }

    public function infobanner(){
        $result = $this->bannerInfoDB->read();
        if($result){
            $result = json_decode($result);
            return json_encode(array("result"=>true, "data"=>$result));
        }else{
            http_response_code(400);
            return json_encode(array("result"=>false, "message"=>"No banners found!"));
        }
    }

    public function addDelExpoToken()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->token) && ($data->token !== "")) {
            $result = $this->delnotiDB->addToken($decodedData->data->id, $data->token);
            if ($result === true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to create a token!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function booking()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $data = json_decode(file_get_contents("php://input"));
        $note = $data->note ?? "";
        if (isset($data->pid) && ($data->pid !== "") && isset($data->msg) && ($data->msg !== "") && isset($data->phone) && ($data->phone !== "") && isset($data->address) && ($data->address !== "")) {
            $phone = strval($data->phone);
            if (strlen($phone) > 10 || strlen($phone) < 10) {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Phone should be a valid 10 digit numbers!"));
            }
            $result = $this->bookingDB->create($decodedData->data->id, $data->pid, $data->phone, $data->address, $data->msg, $note);
            if ($result === true) {
                return json_encode(array("result" => true));
            } else if ($result) {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => $result));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to place booking!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function cancelBooking()
    {
        $data = json_decode(file_get_contents("php://input"));
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        if (isset($data->id) && ($data->id !== "")) {
            $result = $this->bookingDB->cancel($decodedData->data->id, $data->id);
            if ($result === true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array('result' => false, "message" => "Unable to cancel booking!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function getBookings()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->bookingDB->read($decodedData->data->id);
        if ($result) {
            return json_encode(array("result" => true, "data" => $result));
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "No bookings found!"));
        }
    }

    public function getPincode()
    {
        $query = "SELECT DISTINCT pincode FROM product";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $temp = explode(",", $row['pincode']);
                foreach($temp as $t){
                    if(in_array($t, $array)){
                        continue;
                    }else{
                        array_push($array, $t);
                    }
                }
            }
            return json_encode(array('result' => true, "data" => $array));
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "No pincodes found!"));
        }
    }

    public function requestProduct()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        if (isset($_POST['pname']) && ($_POST['pname'] !== "") && isset($_POST['msg']) && ($_POST['msg'] !== "") && isset($_POST['phone']) && ($_POST['phone'] !== "") && isset($_FILES['image']['name']) && ($_FILES['image']['name'] !== "")) {
            $productName = $this->conn->real_escape_string($_POST['pname']);
            $msg = $this->conn->real_escape_string($_POST['msg']);
            $phone = $this->conn->real_escape_string($_POST['phone']);

            if(strlen($phone) <> 10){
                http_response_code(400);
                return json_encode(array("result"=>false, "message"=>"Phone should be a valid 10 digit number!"));
            }

            $query = "SELECT * FROM productrequest where productname='$productName' and `message`='$msg' and uid=".$decodedData->data->id;
            $result = $this->conn->query($query);
            if ($result->num_rows > 0) {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Product Request already exist!"));
            } else {
                $validateImage = $this->validateImage();
                if ($validateImage === true) {
                    $query = "INSERT INTO productrequest SET productname='$productName', `message`='$msg', phone=$phone, `image`='" . $this->imageDest . "', uid=".$decodedData->data->id;
                    $result = $this->conn->query($query);
                    if ($this->conn->affected_rows > 0) {
                        return json_encode(array("result" => true));
                    } else {
                        http_response_code(400);
                        return json_encode(array("result" => false, "message" => "Unable to make a request!"));
                    }
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => $validateImage));
                }
            }
        } else {
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function feedback()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->msg) && ($data->msg != "")) {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $result = $this->customerDB->updateFeedback($data->msg, $decodedData->data->id);
            if ($result === true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to send feedback!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function logout()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $id = $decodedData->data->id;
        $result = $this->notiTokenDB->logout($id);
        if ($result) {
            return json_encode(array("result" => true));
        }
    }

    public function rateDeliveryPartner()
    {
        $data = json_decode(file_get_contents("php://input"));
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        if (isset($data->id) && ($data->id != "") && isset($data->rating) && ($data->rating != "")) {
            $result = self::$ratingDB->riderRating($data->id, $decodedData->data->id, $data->rating);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to rate rider!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function productRating()
    {
        $data = json_decode(file_get_contents("php://input"));
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        if (isset($data->pid) && ($data->pid != "") && isset($data->rating) && ($data->rating != "")) {
            $result = self::$ratingDB->productRating($decodedData->data->id, $data->pid, $data->rating);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to rate product!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function editUserProfile()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->username) && isset($data->password) && isset($data->phone) && isset($data->name)) {
            if (($data->username != "") && ($data->password != "") && ($data->phone != "") && ($data->name != "")) {
                $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
                $result = $this->loginDB->update($decodedData->data->id, $data->username, $data->name, $data->password, $data->phone);
                if ($result) {
                    return json_encode(array("result" => true));
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "Unable to update user details!"));
                }
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Invalid arguments"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments"));
        }
    }

    public function userAddress()
    {
        $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
        $result = $this->addressDB->read($decodedData->data->id);

        if ($result) {
            return json_encode(array("result" => true, "data" => $result, "isMore" => false));
        } else {
            http_response_code(404);
            return json_encode(array("result" => false, "message" => "Address not found!"));
        }
    }

    public function addUserAddress()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->address) && $data->address != "") {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $result = $this->addressDB->add($decodedData->data->id, $data->address);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to add address!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments"));
        }
    }

    public function updateUserAddress()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id) && isset($data->address) && $data->id != "" && $data->address != "") {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $result = $this->addressDB->update($data->id, $decodedData->data->id, $data->address);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to update address!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments"));
        }
    }

    public function removeUserAddress()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id) && $data->id != "") {
            $result = $this->addressDB->delete($data->id);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to delete address!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguemnts"));
        }
    }

    public function cartInc()
    {
        $data = json_decode(file_get_contents("php://input"));
        if ((isset($data->id)) && ($data->id != "")) {
            $result = $this->cartDB->increment($data->id);
            if ($result == true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to update the data!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function cartDec()
    {
        $data = json_decode(file_get_contents("php://input"));
        if ((isset($data->id)) && ($data->id != "")) {
            $result = $this->cartDB->decrement($data->id);
            if ($result == true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to update the data!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function removeCart()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->id) && ($data->id != "")) {
            $result = $this->cartDB->delete($data->id);
            if ($result == true) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to delete product from cart"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function cart()
    {
        if ($this->app->request->getMethod() == "get") {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $json = $this->cartDB->read($decodedData->data->id);
            if ($json) {
                return json_encode(["result" => true, "data" => $json, "isMore" => false]);
            } else {
                return json_encode(array("result" => false, "message" => "No products found!"));
            }
        } else if ($this->app->request->getMethod() == "post") {
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->pid) && ($data->pid != "")) {
                $subProductId = isset($data->subpid) && ($data->subpid != "") ? $data->subpid : "";
                $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
                $result = $this->cartDB->add($decodedData->data->id, $data->pid, $subProductId);
                if ($result === true) {
                    $json = $this->cartDB->read($decodedData->data->id);
                    return json_encode(array("result" => true, "data" => $json));
                } else if (gettype($result) === "string") {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => $result));
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "Unable to add to cart!"));
                }
            } else {
                http_response_code(400);
                return json_encode(array('result' => false, "message" => "Invalid arguments"));
            }
        }
    }

    public function expoNotifications(array $data, $token)
    {
        $channelName = "default";
        try {
            $unique = $token;
            $recipant = "ExponentPushToken[$unique]";
            $notification = ["title" => $data[0]['title'], 'body' => $data[0]['msg']];
            $this->app->expo->subscribe($channelName, $recipant);
            $this->app->expo->notify([$channelName], $notification);
        } catch (Exception $e) {
            return json_encode($e->getMessage());
        }
    }

    public function addExpoToken()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->token) && ($data->token != "")) {
            $decodedData = JWT::decode($this->token, $this->secretKey, array("HS256"));
            $result = $this->notiTokenDB->add($decodedData->data->id, $data->token);
            if ($result) {
                return json_encode(array("result" => true));
            } else {
                http_response_code(400);
                return json_encode(array("result" => false, "message" => "Unable to add token!"));
            }
        } else {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => "Invalid arguments!"));
        }
    }

    public function getBanner()
    {
        $query = "SELECT * FROM banner";
        $result = $this->conn->query($query);
        $array = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($array, $row);
            }
            return json_encode($array);
        } else {
            http_response_code(400);
            return json_encode(array("searchResult" => false, "message" => "No Results found!"));
        }
    }

    public function getRandomCategories()
    {
        try {
            if (isset($_GET['rows']) && ($_GET['rows'] != "")) {
                $array = [];
                $query = "select * from category where status=1 order by(case id when 3 then 1 when 2 then 2 when 4 then 3 when 13 then 4 else 5 end) asc";
                $result = $this->conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($array, $row);
                    }
                }
                $array = array_slice($array, 0, $_GET['rows']);
                $array = json_encode($array);
                return $array;
            } else {
                throw new Exception("Not enough argument found!");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getSearchProduct()
    {
        if (isset($_GET['p']) && ($_GET['p'] !== "")) {
            $p = $_GET['p'];
        }
        if ((isset($_GET['query'])) && ($_GET['query'] != "")) {
            $json = $this->apDB->getSearchProduct("product", $_GET['query'], $this->pincode, isset($p) ? $p : "");
            if ($json) {
                return json_encode(array("result" => true, "data" => $json['data'], 'isMore' => $json['isMore']));
            } else {
                http_response_code(400);
                return json_encode(["result" => false, "message" => "No products found!"]);
            }
        } else {
            http_response_code(400);
            return json_encode("Invalid arguments!");
        }
    }

    public function getRandomSubCategories()
    {
        try {
            if (isset($_GET['rows']) && ($_GET['rows'] != "")) {
                $array = [];
                $query = "select * from subcategory where subcategory.status = 1 order by(case cat_id when 3 then 1 when 2 then 2 when 4 then 3 when 13 then 4 else 5 end) asc";
                $result = $this->conn->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($array, $row);
                    }
                }
                $array = array_slice($array, 0, $_GET['rows']);
                $json = json_encode($array);
                return $json;
            } else {
                throw new Exception("Not enough argument found!");
            }
        } catch (Exception $e) {
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
            } else if (isset($_GET['cid'])) {
                if ($_GET['cid'] != "") {
                    return $this->sDB->getSubCategoryByCid("subcategory", $_GET['cid']);
                } else {
                    throw new Exception("Invalid CID");
                }
            } else {
                return $this->sDB->apiRead("subcategory");
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

            if (isset($_GET['p']) && $_GET['p'] !== "") {
                $p = $_GET['p'];
            }
            if (isset($_GET['id'])) {
                if ($_GET['id'] !== "") {
                    $result = $this->apDB->getProductById("product", $_GET['id'], $this->pincode, isset($p) ? $p : "");
                    if ($result) {
                        return json_encode(array("rseult" => true, "data" => $result['data'], "isMore" => $result['isMore']));
                    } else {
                        http_response_code(400);
                        return json_encode(array("result" => false, "message" => "No products found in your location!"));
                    }
                } else {
                    throw new Exception("Invalid ID");
                }
            } else if (isset($_GET['cid'])) {
                if ($_GET['cid'] !== "") {
                    $result = $this->apDB->readByCid("product", $_GET['cid'], $this->pincode, isset($p) ? $p : "");
                    if ($result) {
                        return json_encode(array("result" => true, "data" => $result['data'], "isMore" => $result['isMore']));
                    } else {
                        http_response_code(400);
                        return json_encode(array("result" => false, "message" => "No products found in your location!"));
                    }
                } else {
                    throw new Exception("Invalid category ID");
                }
            } else if (isset($_GET['sid'])) {
                if ($_GET['sid'] !== "") {
                    $result = $this->apDB->readBySid("product", $_GET['sid'], $this->pincode, isset($p) ? $p : "");
                    if ($result) {
                        return json_encode(array("result" => true, "data" => $result['data'], "isMore" => $result['isMore']));
                    } else {
                        http_response_code(400);
                        return json_encode(array("result" => false, "message" => "No products found in your location!"));
                    }
                } else {
                    throw new Exception("Invalid subcategory ID");
                }
            } else {
                $result = $this->apDB->read('product', $this->pincode, isset($p) ? $p : "");
                if ($result) {
                    return json_encode(array("result" => true, "data" => $result['data'], 'isMore' => $result['isMore']));
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "No products found in your location!"));
                }
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode(array("result" => false, "message" => $e->getMessage()));
        }
    }

    public function getFoodItems()
    {
        return $this->apDB->getFoodItems($this->pincode);
    }

    public function getRandomFoodItems()
    {
        try {
            if (isset($_GET['rows']) && ($_GET['rows'] != "")) {
                $json = $this->apDB->getFoodItems($this->pincode);
                if ($json) {
                    $array = json_decode($json);
                    shuffle($array);
                    $array = array_slice($array, 0, $_GET['rows']);
                    $json = json_encode($array);
                    return $json;
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "No products found in your location!"));
                }
            } else {
                throw new Exception("Not enough arguments found");
            }
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode($e->getMessage());
        }
    }

    public function getRandomProducts()
    {
        try {
            if (isset($_GET['p']) && ($_GET['p'] !== "")) {
                $p = $_GET['p'];
            }

            if (isset($_POST['rows']) && $_POST['rows'] != "") {
                $json = $this->apDB->read('product', $this->pincode, isset($p) ? $p : "");
                if ($json) {
                    shuffle($json['data']);
                    $array = array_slice($json['data'], 0, $_POST['rows']);
                    return json_encode(array("result" => true, "data" => $array, "isMore" => $json['isMore']));
                } else {
                    http_response_code(400);
                    return json_encode(array("result" => false, "message" => "No products found in your location!"));
                }
            } else {
                throw new Exception("Not enough arguments found");
            }
        } catch (Exception $e) {
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
                return $this->cDB->apiRead("category");
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

    public function validateImage()
    {
        function generateRandomString($length = 25) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $fileName = generateRandomString(15);
        $targetDir = Application::$ROOT_DIR . "/public/assets/images/request/";
        $targetFile = sprintf("%s%s.%s", $targetDir, $fileName, pathinfo($_FILES['image']['name'])['extension']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //check if image is an actual image
        if (isset($_POST['submit'])) {
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                return "File is not an image";
            }
        }

        //check if image file already exist
        if (file_exists($targetFile)) {
            $this->validateImage();
        }
        // limit the file size
        if ($_FILES['image']['size'] > 5000000) {
            $uploadOk = 0;
            return "File size too large";
        }

        if ($imageFileType != "jpg" && $imageFileType != 'png' && $imageFileType != "jpeg") {
            $uploadOk = 0;
            return "Only jpg, png and jpeg file formats are allowed";
        }

        if ($uploadOk == 0) {
            return "Your file didn't uploaded for some reasons";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $this->imageDest = sprintf("/assets/images/request/%s.%s", $fileName, pathinfo($_FILES['image']['name'])['extension']);
                return true;
            } else {
                return false;
            }
        }
    }
}
