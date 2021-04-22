<?php

require __DIR__. '/../vendor/autoload.php';

use app\core\Application;
use app\controllers\CategoryController;
use app\controllers\ProductController;
use app\controllers\ProfileController;
use app\controllers\ApiController;
use app\controllers\AreaController;
use app\controllers\TimeSlotsController;
use app\controllers\NotificationsController;
use app\controllers\CountryCodeController;
use app\controllers\CouponController;
use app\controllers\PaymentController;

use app\models\DB;

$app = new Application(dirname(__DIR__));
$db = new DB();

// /profile controlling routes
$app->router->get('/login', [ProfileController::class, 'login']);
$app->router->post('/login', [ProfileController::class, 'login']);
$app->router->get("/logout", [ProfileController::class, "logout"]);

// /categorylist routes
$app->router->get('/', [CategoryController::class, 'home']);
$app->router->get("/categorylist/add", [CategoryController::class, "add"]);
$app->router->post("/categorylist/add", [CategoryController::class, "add"]);
$app->router->get("/categorylist", [CategoryController::class, "read"]);
$app->router->get("/categorylist/delete", [CategoryController::class, "delete"]);
$app->router->get("/categorylist/edit", [CategoryController::class, "update"]);
$app->router->post("/categorylist/edit", [CategoryController::class, "update"]);

// /productlist routes
$app->router->get("/productlist", [ProductController::class, "read"]);
$app->router->get("/productlist/add", [ProductController::class, "create"]);
$app->router->post("/productlist/add", [ProductController::class, "create"]);
$app->router->get("/productlist/edit", [ProductController::class, "update"]);
$app->router->post("/productlist/edit", [ProductController::class, "update"]);
$app->router->get("/productlist/delete", [ProductController::class, "delete"]);

// /api
$app->router->get("/api/product", [ApiController::class, "getProducts"]);
$app->router->get("/api/category", [ApiController::class, "getCategories"]);
$app->router->get("/api/pushednotifies", [ApiController::class, "getNotifications"]);

// /areaList
$app->router->get("/arealist", [AreaController::class, "read"]);
$app->router->get("/arealist/add", [AreaController::class, "create"]);

// /timeslots
$app->router->get("/timeslots", [TimeSlotsController::class, "read"]);
$app->router->get("/timeslots/add", [TimeSlotsController::class, "create"]);

// /coupon
$app->router->get("/couponlist", [CouponController::class, "read"]);
$app->router->get("/couponlist/add", [CouponController::class, "create"]);

// /notifications
$app->router->get("/notifications", [NotificationsController::class, "read"]);
$app->router->get("/notifications/add", [NotificationsController::class, "create"]);
$app->router->get("/notifications/delete", [NotificationsController::class, "delete"]);
$app->router->get("/notifications/push", [NotificationsController::class, "push"]);

// /countrycode
$app->router->get("/countrycode", [CountryCodeController::class, "read"]);
$app->router->get("/countrycode/add", [CountryCodeController::class, "create"]);

// /paymentlist
$app->router->get("/paymentlist", [PaymentController::class, "read"]);


$app->run();


