<?php
require __DIR__. '/../vendor/autoload.php';

use app\core\Application;
use app\controllers\CategoryController;

$app = new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class, 'home']);

// $app->router->get('/contact', 'contact');
$app->router->get("/categorylist", [CategoryController::class, "categoryList"]);
$app->router->get("/categorylist/delete", [CategoryController::class, "delete"]);
$app->router->get("/categorylist/edit", [CategoryController::class, "edit"]);
$app->router->post("/categorylist/edit", [CategoryController::class, "update"]);
$app->run();


