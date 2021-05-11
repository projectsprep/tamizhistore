<?php

namespace app\controllers;

header("Content-type: application/json");

use app\core\Controller;
use app\models\ProductsModel;
use app\models\CategoryModel;
use app\models\NotificationsModel;
use app\models\DB;
use app\core\Application;
use app\models\CouponModel;
use app\models\AreaModel;
use app\models\TimeSlotsModel;
use app\models\PaymentModel;
use app\models\CountryCodeModel;
use Exception;

session_start();
if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

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
    }

    public function getCodelist()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->codeDB->getCodeById("code", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->codeDB->read("code");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getProduct()
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
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->tsDB->read("timeslot");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getArea()
    {
        try {
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->areaDB->getAreaById("area_db", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->areaDB->read("area_db");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCoupons()
    {
        try{
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->couponDB->getCouponById("tbl_coupon", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->couponDB->read("tbl_coupon");
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getPayment()
    {
        try{
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->paymentDB->getPaymentById("payment_list", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->paymentDB->read("payment_list");
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getCategory()
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
        if(isset($_POST['id'])){
            return $this->pDB->getSubcategoryNames("subcategory", $_POST['id']);
        }else{
            return json_encode(array("Message"=>"Invalid ID"));
        }
    }

    public function getProducts()
    {
        try{
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== "") {
                    return $this->pDB->getProductById("product", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->pDB->read('product');
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getCategories()
    {
        try{
            if (isset($_POST['id'])) {
                if ($_POST['id'] !== '') {
                    return $this->cDB->getCategoryById("category", $_POST['id']);
                }else{
                    throw new Exception("Invalid ID");
                }
            } else {
                return $this->cDB->read("category");
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getCategoryNames()
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
        try{
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
            }else{
                throw new Exception("Invalid Request");
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function pushedNotifications()
    {
        return $this->nDB->pushedNotifies("noti");
    }
}
