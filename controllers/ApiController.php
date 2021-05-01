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

session_start();
if(!(isset($_COOKIE['user']) && isset($_SESSION['user']))){
    header("Location: /login");
}

class ApiController extends Controller{
    private DB $db;
    private Application $app;
    private ProductsModel $pDB;
    private CategoryModel $cDB;
    private NotificationsModel $nDB;
    private CouponModel $couponDB;
    public function __construct(){
        $this->db = new DB();
        $this->conn = $this->db->conn();
        $this->app = new Application(dirname(__DIR__));
        $this->pDB = new ProductsModel();
        $this->cDB = new CategoryModel();
        $this->nDB = new NotificationsModel();
        $this->couponDB = new CouponModel();
    }

    public function getProduct(){
        $query = "select p.id, p.pname, p.pimg, p.sname, category.catname, subcategory.name, p.pgms, p.pprice, p.stock, p.status from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc limit 10";
        $result = $this->conn->query($query);
        $output = "";
        if($result->num_rows > 0){
            $i = 1;
            while($row = $result->fetch_assoc()){
                $output .= "<tr>
                <td>
                    <span>
                        " . $i++ . "
                    </span>
                </td>
                <td>
                    <h5 class='font-size-14 mb-1'>".$row['pname']."</h5>
                </td>
                <td>
                        <img src='".$row['pimg']."' class='img-thumbnail rounded' alt=''>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".$row['sname']."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".$row['catname']."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".$row['name']."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".$row['pprice']."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".$row['pgms']."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".($row['stock'] ? 'Yes' : 'No')."</h5>
                    </div>
                </td>
                <td>
                    <div>
                        <h5 class='font-size-14 mb-1'>".($row['status'] ? 'Published' : 'Unpublished')."</h5>
                    </div>
                </td>
                <td>
                    <a href='/productlist/delete?id=".$row['id']."' class='text-danger'><i class='bx bx-trash-alt'></i></a>
                    <a href='/productlist/edit?id=<?=".$row['id']."'><i class='bx bx-edit'></i></a>
                </td>
                
            </tr>";
            }
        }else{
            $output .= "No results found";
        }
        $this->conn->close();
        return json_encode($output);
    }

    public function getCoupons(){
        if(isset($_GET['id'])){
            return $this->couponDB->getCouponById("tbl_coupon", $_GET['id']);
        }else{
            return $this->couponDB->read("tbl_coupon");
        }
    }

    public function getCategory(){
        $query = "SELECT * FROM category ORDER BY id DESC";
        $result = $this->conn->query($query);
        $output = "";
        if($result->num_rows > 0){
            $i = 1;
            while($row=$result->fetch_assoc()){
                $output .="<tr>
                            <td>
                                <span>".
                                    $i++
                                ."</span>
                            </td>
                            <td>
                                <h5 class='font-size-14 mb-1'>" .$row['catname'] . "</h5>
                            </td>
                            <td>
                                <img src='". $row['catimg']."' class='img-thumbnail rounded' alt=''>
                            </td>
                            <td>
                                <div>
                                    <h5 class='font-size-14 mb-1'>". $this->conn->query('select * from subcategory where cat_id = ' . $row['id'] . ';')->num_rows ."</h5>
                                </div>
                            </td>
                            <td>
                                <a href='/categorylist/delete?id=". $row['id']."'class='text-danger'><i class='bx bx-trash-alt'></i></a>
                                <a href='#'><i class='bx bx-edit editcategory' id=". $row['id']."></i></a>
                            </td>
                        </tr>
                ";
            }                                                    
        }else{
            $output .= "No results found";
        }
        $this->conn->close();
        return json_encode($output);
    }

    public function getSubcategoryNames(){
        return $this->pDB->getSubcategoryNames("subcategory", $_POST['id']);
    }

    public function getProducts(){
        if(isset($_GET['id'])){
            $id = htmlspecialchars($_GET['id']);
            return $this->pDB->getProductById("product", $id);
        }else{
            return $this->pDB->read('product');
        }
    }

    public function getCategories(){
        if(isset($_GET['id'])){
            $id = htmlspecialchars($_GET['id']);
            return $this->cDB->getCategoryById("category", $id);
        }else{
            return $this->cDB->read("category");
        }
    }

    public function getCategoryNames(){
        return $this->pDB->getCategoryNames("category");
    }
    
    public function getNotifications(){
        // return $this->nDB->pushedNotifies("noti");
        if(isset($_GET['view'])){
            if($_GET['view'] != ""){
                $updateQuery = "UPDATE noti SET is_seen=1 WHERE pushed=1";
                $this->conn->query($updateQuery);
            }
            $query = "SELECT * FROM noti where pushed=1 ORDER BY id DESC LIMIT 5";
            $result = $this->conn->query($query);
            $output = "";
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $output .= "<a href='#' class='text-reset notification-item'>
                    <div class='media'>
                        <div class='avatar-xs me-3'>
                            <span class='avatar-title bg-primary rounded-circle font-size-16'>
                                <i class='bx bx-cart'></i>
                            </span>
                        </div>
                        <div class='media-body'>
                            <h6 class='mt-0 mb-1' key='t-your-order'>". $row['title']."</h6>
                            <div class='font-size-12 text-muted'>
                                <p class='mb-1' key='t-grammer'>". $row['msg']."</p>
                                <p class='mb-0'><i class='mdi mdi-clock-outline'></i> <span key='t-min-ago'>3 min ago</span></p>
                            </div>
                        </div>
                    </div>
                </a>";
                }
            }else{
                $output .= "No Notifications found";
            }
            $this->conn->close();
            $query1 = "SELECT * FROM noti WHERE pushed=1 and is_seen=0";
            $result1 = $this->conn->query($query1);
            $count = $result1->num_rows;

            $data = array(
                "notification"=>$output,
                "unseenNotification"=>$count
            );
            $this->conn->close();
            return json_encode($data);
        }
    }
    
    public function pushedNotifications(){
        return $this->nDB->pushedNotifies("noti");
    }
}