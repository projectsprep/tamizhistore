<?php
require __DIR__. '/../vendor/autoload.php';
// error_reporting(0);

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
use app\controllers\UserProfileController;
use app\models\DB;
use app\controllers\OrdersController;
use app\controllers\AppController;
use app\controllers\SubCategoryController;
use app\controllers\CustomersController;
use app\controllers\DeliveryBoysController;

$app = new Application(dirname(__DIR__));
$db = new DB();

// /profile controlling routes
$app->router->get('/login', [ProfileController::class, 'login']);
$app->router->get('/maintenance', "maintenance");
$app->router->post('/login', [ProfileController::class, 'login']);
$app->router->get("/logout", [ProfileController::class, "logout"]);
$app->router->get("/login/forgotpassword", [ProfileController::class, "forgotPassword"]);
$app->router->post("/login/forgotpassword", [ProfileController::class, "forgotPassword"]);
$app->router->get("/resetpassword", [ProfileController::class, "resetPassword"]);
$app->router->post("/resetpassword", [ProfileController::class, "resetPassword"]);

//user profile controller
$app->router->get("/profile", [UserProfileController::class, "profile"]);
$app->router->post("/profile", [UserProfileController::class, "profile"]);

// /categorylist routes
$app->router->get('/', [CategoryController::class, 'home']);
$app->router->get("/categorylist/add", [CategoryController::class, "add"]);
$app->router->post("/categorylist/add", [CategoryController::class, "add"]);
$app->router->get("/categorylist", [CategoryController::class, "read"]);
$app->router->post("/categorylist/delete", [CategoryController::class, "delete"]);
// $app->router->get("/categorylist/edit", [CategoryController::class, "update"]);
$app->router->post("/categorylist/edit", [CategoryController::class, "update"]);


// /subcategorylist routes
$app->router->get("/subcategorylist", [SubCategoryController::class, "read"]);
$app->router->get("/subcategorylist/add", [SubCategoryController::class, "create"]);
$app->router->post("/subcategorylist/add", [SubCategoryController::class, "create"]);
$app->router->post("/subcategorylist/edit", [SubCategoryController::class, "update"]);
$app->router->post("/subcategorylist/delete", [SubCategoryController::class, "delete"]);

// /productlist routes
$app->router->get("/productlist", [ProductController::class, "read"]);
$app->router->get("/productlist/add", [ProductController::class, "create"]);
$app->router->post("/productlist/add", [ProductController::class, "create"]);
$app->router->get("/productlist/edit", [ProductController::class, "update"]);
$app->router->post("/productlist/edit", [ProductController::class, "update"]);
$app->router->post("/productlist/delete", [ProductController::class, "delete"]);

// /api
$app->router->get("/api/banner", [ApiController::class, "getBanner"]);
$app->router->post("/api/product", [ApiController::class, "getProductWithList"]);
$app->router->get("/api/getsearchproduct", [ApiController::class, "getSearchProduct"]);
$app->router->get("/api/category", [ApiController::class, "getCategories"]);
$app->router->get("/api/category/random", [ApiController::class, "getRandomCategories"]);
$app->router->get("/api/subcategory", [ApiController::class, "getSubCategories"]);
$app->router->get("/api/subcategory/random", [ApiController::class, "getRandomSubCategories"]);
$app->router->post("/api/coupons", [ApiController::class, "getCoupons"]);
$app->router->post("/api/area", [ApiController::class, "getArea"]);
$app->router->post("/api/getcategory", [ApiController::class, "getCategoryWithList"]);
$app->router->post("/api/timeslot", [ApiController::class, "getTimeslot"]);
$app->router->post("/api/payment", [ApiController::class, "getPayment"]);
$app->router->post("/api/codelist", [ApiController::class, "getCodelist"]);
$app->router->post("/api/getcategorynames", [ApiController::class, "getCategoryNamesWithList"]);
$app->router->post("/api/getsubcategorynames", [ApiController::class, "getSubcategoryNames"]);
$app->router->get("/api/getproduct", [ApiController::class, "getProducts"]);
$app->router->post("/api/getfood", [ApiController::class, "getFoodItems"]);
$app->router->get("/api/getfooditems/random", [ApiController::class, "getRandomFoodItems"]);
$app->router->post("/api/getproduct/random", [ApiController::class, "getRandomProducts"]);
$app->router->post("/api/pushednotifies", [ApiController::class, "getNotifications"]);
$app->router->post("/api/pushednotifiessite", [ApiController::class, "pushedNotifications"]);
$app->router->post("/api/expotoken", [ApiController::class, "expoNotifications"]);


// /areaList
$app->router->get("/arealist", [AreaController::class, "read"]);
$app->router->get("/arealist/add", [AreaController::class, "create"]);
$app->router->post("/arealist/add", [AreaController::class, "create"]);
$app->router->post("/arealist/edit", [AreaController::class, "edit"]);
$app->router->post("/arealist/delete", [AreaController::class, "delete"]);

// /timeslots
$app->router->get("/timeslots", [TimeSlotsController::class, "read"]);
$app->router->get("/timeslots/add", [TimeSlotsController::class, "create"]);
$app->router->post("/timeslots/edit", [TimeSlotsController::class, "edit"]);
$app->router->post("/timeslots/add", [TimeSlotsController::class, "create"]);
$app->router->post("/timeslots/delete", [TimeSlotsController::class, "delete"]);

// /coupon
$app->router->get("/couponlist", [CouponController::class, "read"]);
$app->router->get("/couponlist/add", [CouponController::class, "create"]);
$app->router->post("/couponlist/add", [CouponController::class, "create"]);
$app->router->post("/couponlist/edit", [CouponController::class, "edit"]);

// /notifications
$app->router->get("/notifications", [NotificationsController::class, "read"]);
$app->router->get("/notifications/add", [NotificationsController::class, "create"]);
$app->router->post("/notifications/add", [NotificationsController::class, "create"]);
$app->router->post("/notifications/delete", [NotificationsController::class, "delete"]);
$app->router->post("/notifications", [NotificationsController::class, "push"]);

// /countrycode
$app->router->get("/countrycode", [CountryCodeController::class, "read"]);
$app->router->get("/countrycode/add", [CountryCodeController::class, "create"]);
$app->router->post("/countrycode/add", [CountryCodeController::class, "create"]);
$app->router->post("/countrycode/edit", [CountryCodeController::class, "update"]);
$app->router->post("/countrycode/delete", [CountryCodeController::class, "delete"]);

// /orders
$app->router->get("/orders", [OrdersController::class, "read"]);
$app->router->get("/orders/pending", [OrdersController::class, "readPending"]);
$app->router->get("/orders/export", [OrdersController::class, 'exportOrders']);

// /paymentlist
$app->router->get("/paymentlist", [PaymentController::class, "read"]);
$app->router->post("/paymentlist/edit", [PaymentController::class, "update"]);

// /app
$app->router->post("/app/assigndeliveryboy", [AppController::class, "assignDeliveryBoy"]);
$app->router->post("/app/riderres", [AppController::class, "deliveryBoyNotiRes"]);
$app->router->post("/app/assignedorders", [AppController::class, "assignedOrders"]);

// /customers
$app->router->get("/customers", [CustomersController::class, 'readCustomers']);
$app->router->get("/customers/feedback", [CustomersController::class, 'readFeedback']);

// /deliveryboys
$app->router->get("/deliveryboys/add", [DeliveryBoysController::class, "create"]);
$app->router->post("/deliveryboys/add", [DeliveryBoysController::class, "create"]);
$app->router->get("/deliveryboys", [DeliveryBoysController::class, "read"]);
$app->router->post("/deliveryboys/delete", [DeliveryBoysController::class, "delete"]);
$app->router->post("/deliveryboys/update", [DeliveryBoysController::class, "update"]);

$app->run();
