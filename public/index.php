<?php

require __DIR__. '/../vendor/autoload.php';

use app\core\Application;
use app\controllers\SiteController;

$app = new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class, 'home']);

// $app->router->get('/contact', 'contact');
$app->router->get("/categorylist", [SiteController::class, "categoryList"]);
$app->run();


