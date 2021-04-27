<?php

namespace app\controllers;

header("Content-type: application/json");
use app\core\Controller;
use app\models\ProductsModel;
use app\models\CategoryModel;
use app\models\NotificationsModel;
use app\models\DB;
use app\core\Application;

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
    public function __construct(){
        $this->db = new DB();
        $this->conn = $this->db->conn();
        $this->app = new Application(dirname(__DIR__));
        $this->pDB = new ProductsModel();
        $this->cDB = new CategoryModel();
        $this->nDB = new NotificationsModel();
    }

    public function getProducts(){
        return $this->pDB->read('product');
    }

    public function getCategories(){
        return $this->cDB->read("category");
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
            $query1 = "SELECT * FROM noti WHERE pushed=1 and is_seen=0";
            $result1 = $this->conn->query($query1);
            $count = $result1->num_rows;

            $data = array(
                "notification"=>$output,
                "unseenNotification"=>$count
            );
            return json_encode($data);
        }
    }
    
    public function pushedNotifications(){
        return $this->nDB->pushedNotifies("noti");
    }
}