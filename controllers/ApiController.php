<?php

namespace app\controllers;

header("Content-type: application/json");
use app\core\Controller;
use app\models\ProductsModel;
use app\models\CategoryModel;
use app\models\NotificationsModel;
use app\core\Application;

if(!(isset($_COOKIE['user'])))
header("Location: /login");

class ApiController extends Controller{
    private Application $app;
    private ProductsModel $pDB;
    private CategoryModel $cDB;
    private NotificationsModel $nDB;
    public function __construct(){
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
        return $this->nDB->pushedNotifies("noti");
    }
}