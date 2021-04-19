<?php

require __DIR__. '/../vendor/autoload.php';

use app\core\Application;
use app\controllers\CategoryController;
use app\controllers\ProductController;
use app\controllers\ProfileController;
use app\models\DB;

$app = new Application(dirname(__DIR__));
$db = new DB();

// /profile controlling routes
$app->router->get('/login', [ProfileController::class, 'login']);
$app->router->post('/login', [ProfileController::class, 'login']);

// /categorylist routes
$app->router->get('/', [CategoryController::class, 'home']);
$app->router->get("/categorylist", [CategoryController::class, "categoryList"]);
$app->router->get("/categorylist/delete", [CategoryController::class, "delete"]);
$app->router->get("/categorylist/edit", [CategoryController::class, "edit"]);
$app->router->post("/categorylist/edit", [CategoryController::class, "update"]);
$app->router->get("/categorylist/add", [CategoryController::class, "add"]);
$app->router->post("/categorylist/add", [CategoryController::class, "upload"]);

// /productlist routes
$app->router->get("/productlist", [ProductController::class, "read"]);
$app->router->get("/productlist/add", [ProductController::class, "add"]);
$app->router->post("/productlist/add", [ProductController::class, "add"]);
$app->router->get("/productlist/edit", [ProductController::class, "edit"]);
$app->router->post("/productlist/edit", [ProductController::class, "edit"]);
$app->router->get("/productlist/delete", [ProductController::class, "delete"]);

$app->run();


