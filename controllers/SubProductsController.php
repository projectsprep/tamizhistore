<?php
namespace app\controllers;

use app\core\Controller;
use app\models\SubProductsModel;
use app\core\Application;
use Exception;

session_start();

if (!(isset($_COOKIE['user']) && isset($_SESSION['user']))) {
    header("Location: /login");
}

  
// Setting set_error_handler
set_error_handler(function ($error_no, $error_msg) {
    echo "Error: [$error_no] $error_msg ";
    echo "\n Now Script will end";
      
    // When error occurred script has to be stoped
    die();
} );

class SubProductsController extends Controller{
    private $db;
    private $app;

    public function __construct()
    {
        $this->db = new SubProductsModel();
        $this->app = new Application(dirname(__DIR__));
    }

    public function getSubProduct(){
        if(isset($_GET['id']) && ($_GET['id'] != "")){
            $json = $this->db->readById($_GET['id']);
            if($json){
                return $json;
            }else{
                http_response_code(400);
                return "No results found";
            }
        }else{
            http_response_code(400);
            return "Invalid ID!";
        }
    }

    public function read(){
        try{
            $json = $this->db->read();
            if($json){
                return $this->render("subproduct/subproductList", $json);
            }else{
                return $this->render("subproduct/subproductList", "", json_encode(array('error'=>"No products found")));
            }
        }catch(Exception $e){
            $msg = urlencode($e->getMessage());
            return header("Location: /?msg=$msg");
        }
    }

    public function update(){
        if(isset($_POST['id']) && ($_POST['id'] != "") && isset($_POST['pprice']) && ($_POST['pprice'] != "") && isset($_POST['unit']) && ($_POST['unit'] != "") && isset($_POST['pid']) && ($_POST['pid'] != "")){
            $result = $this->db->update($_POST['id'], $_POST['pid'], $_POST['pprice'], $_POST['unit']);
            if($result){
                $msg = urlencode("Subproduct updated successfully!");
                return header("refresh:0;url=/subproducts?msg=$msg");
            }else{
                $msg = urlencode("unable to udpate subproduct!");
                return header("refresh:0;url=/subproducts?msg=$msg");
            }
        }else{
            $msg = urlencode("All input fields are required!");
            return header("refresh:0;url=/subproducts?msg=$msg");
        }
    }  

    public function delete(){
        if(isset($_POST['id']) && ($_POST['id'] != "")){
            $result = $this->db->delete($_POST['id']);
            if($result){
                $msg = urlencode("Subproduct deleted successfully!");
                return header("refresh:0;url=/subproducts?msg=$msg");
            }else{
                $msg = urlencode("unable to delete subproduct!");
                return header("refresh:0;url=/subproducts?msg=$msg");
            }
        }else{
            $msg = urlencode("Invalid ID!");
            return header("refresh:0;url=/subproducts?msg=$msg");
        }
    }

    public function add(){
        if($this->app->request->getMethod() === "get"){
            return $this->render("subproduct/addsubproduct");
        }else if($this->app->request->getMethod() === "post"){
            if(isset($_POST['pprice']) && ($_POST['pprice'] != "") && isset($_POST['unit']) && ($_POST['unit'] != "") && isset($_POST['pid']) && ($_POST['pid'] != "")){
                $result = $this->db->add($_POST['pid'], $_POST['pprice'], $_POST['unit']);
                if($result){
                    $msg = urlencode("Subproduct added successfully!");
                    return header("refresh:0;url=/subproducts?msg=$msg");
                }else{
                    $msg = urlencode("Unable to add subproduct!");
                    return header("refresh:0;url=/subproducts/add?msg=$msg");
                }
            }else{
                $msg = urlencode("All input fields are required!");
                return header("refresh:0;url=/subproducts/add?msg=$msg");
            }
        }
    }
}