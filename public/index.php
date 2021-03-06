<?php
require __DIR__. '/../vendor/autoload.php';
error_reporting(0);

$expo = \ExponentPhpSDK\Expo::normalSetup();

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
use app\controllers\LoginController;
use app\controllers\ProductRequestController;
use app\controllers\RiderOrdersController;
use app\controllers\SettingsController;
use app\controllers\BannerController;
use app\controllers\StepsInfoController;
use app\controllers\SubProductsController;

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
$app->router->post("/profile/test", [UserProfileController::class, "test"]);

// /categorylist routes
$app->router->get('/', [CategoryController::class, 'home']);
$app->router->get("/categorylist/add", [CategoryController::class, "add"]);
$app->router->post("/categorylist/add", [CategoryController::class, "add"]);
$app->router->get("/categorylist", [CategoryController::class, "read"]);
$app->router->post("/categorylist/delete", [CategoryController::class, "delete"]);
// $app->router->get("/categorylist/edit", [CategoryController::class, "update"]);
$app->router->post("/categorylist/edit", [CategoryController::class, "update"]);
$app->router->get("/category", [CategoryController::class, "getCategories"]);


// /subcategorylist routes
$app->router->get("/subcategorylist", [SubCategoryController::class, "read"]);
$app->router->get("/subcategorylist/add", [SubCategoryController::class, "create"]);
$app->router->post("/subcategorylist/add", [SubCategoryController::class, "create"]);
$app->router->post("/subcategorylist/edit", [SubCategoryController::class, "update"]);
$app->router->post("/subcategorylist/delete", [SubCategoryController::class, "delete"]);
$app->router->get("/subcategory", [SubCategoryController::class, "getSubCategories"]);
$app->router->get("/getsubcategorynames", [ProductController::class, "getSubcategoryNames"]);


// /productlist routes
$app->router->get("/productlist", [ProductController::class, "read"]);
$app->router->get("/getproduct", [ProductController::class, "getproducts"]);
$app->router->get("/getcategorynames", [ProductController::class, "getCategoryNamesWithList"]);
$app->router->get("/getsubcategorynames", [ProductController::class, "getSubcategoryNames"]);
$app->router->get("/productlist/add", [ProductController::class, "create"]);
$app->router->post("/productlist/add", [ProductController::class, "create"]);
// $app->router->get("/productlist/edit", [ProductController::class, "update"]);
$app->router->post("/productlist/edit", [ProductController::class, "update"]);
$app->router->post("/productlist/delete", [ProductController::class, "delete"]);
$app->router->get("/productlist/fooditems", [ProductController::class, "foodItems"]);
// /api
$app->router->get("/api/banner", [ApiController::class, "getBanner"]);
$app->router->post("/api/product", [ApiController::class, "getProductWithList"]);
$app->router->get("/api/getsearchproduct", [ApiController::class, "getSearchProduct"]);
$app->router->get("/api/category", [ApiController::class, "getCategories"]);
$app->router->get("/api/category/random", [ApiController::class, "getRandomCategories"]);
$app->router->get("/api/subcategory", [ApiController::class, "getSubCategories"]);
$app->router->get("/api/subcategory/random", [ApiController::class, "getRandomSubCategories"]);
$app->router->post("/api/getcategory", [ApiController::class, "getCategoryWithList"]);
$app->router->post("/api/getcategorynames", [ApiController::class, "getCategoryNamesWithList"]);
$app->router->post("/api/getsubcategorynames", [ApiController::class, "getSubcategoryNames"]);
$app->router->get("/api/getproduct", [ApiController::class, "getProducts"]);
$app->router->post("/api/getfood", [ApiController::class, "getFoodItems"]);
$app->router->get("/api/getfooditems/random", [ApiController::class, "getRandomFoodItems"]);
$app->router->post("/api/getproduct/random", [ApiController::class, "getRandomProducts"]);
$app->router->post("/api/pushednotifies", [NotificationsController::class, "getNotifications"]);
$app->router->post("/api/pushednotifiessite", [ApiController::class, "pushedNotifications"]);
$app->router->post("/api/expotoken", [ApiController::class, "expoNotifications"]);
$app->router->post("/api/cart", [ApiController::class, "cart"]);
$app->router->get("/api/cart", [ApiController::class, "cart"]);
$app->router->post("/api/cart/inc", [ApiController::class, "cartInc"]);
$app->router->post("/api/cart/dec", [ApiController::class, "cartDec"]);
$app->router->post("/api/cart/remove", [ApiController::class, "removeCart"]);
$app->router->post("/api/login", [LoginController::class, "login"]);
$app->router->post("/api/dellogin", [LoginController::class, "deliveryBoyLogin"]);
$app->router->get("/api/useraddress", [ApiController::class, "userAddress"]);
$app->router->post("/api/useraddress", [ApiController::class, "addUserAddress"]);
$app->router->post("/api/useraddress/update", [ApiController::class, "updateUserAddress"]);
$app->router->post("/api/useraddress/remove", [ApiController::class, "removeUserAddress"]);
$app->router->post("/api/userprofile", [ApiController::class, "editUserProfile"]);
$app->router->post("/api/makeorder", [AppController::class, "makeOrder"]);
$app->router->get("/api/userorders", [AppController::class, "userOrders"]);
$app->router->post("/api/cartorders", [AppController::class, "cartOrders"]);
$app->router->post("/api/productrating", [ApiController::class, "productRating"]);
$app->router->get("/api/getproductrating", [ApiController::class, "getProductRating"]);
$app->router->post("/api/cancelorder", [AppController::class, "cancelOrder"]);
$app->router->post("/api/addexpotoken", [ApiController::class, "addExpoToken"]);
$app->router->post("/api/ratedeliverypartner", [ApiController::class, "rateDeliveryPartner"]);
$app->router->post("/api/logout", [ApiController::class, "logout"]);
$app->router->post("/api/feedback", [ApiController::class, "feedback"]);
$app->router->post("/api/requestproduct", [ApiController::class, "requestProduct"]);
$app->router->get("/api/getpincode", [ApiController::class, "getPincode"]);
$app->router->post("/api/booking", [ApiController::class, "booking"]);
$app->router->post("/api/cancelbooking", [ApiController::class, "cancelBooking"]);
$app->router->get("/api/getbookings", [ApiController::class, "getBookings"]);
$app->router->post("/api/adddelexpotoken", [ApiController::class, "addDelExpoToken"]);
$app->router->get("/api/infobanner", [ApiController::class, "infobanner"]);
$app->router->post("/api/test", [ApiController::class, "test"]);

// /areaList
$app->router->get("/area", [AreaController::class, "getArea"]);
$app->router->get("/arealist", [AreaController::class, "read"]);
$app->router->get("/arealist/add", [AreaController::class, "create"]);
$app->router->post("/arealist/add", [AreaController::class, "create"]);
$app->router->post("/arealist/edit", [AreaController::class, "edit"]);
$app->router->post("/arealist/delete", [AreaController::class, "delete"]);

// /timeslots
$app->router->get("/timeslot", [TimeSlotsController::class, "getTimeslot"]);
$app->router->get("/timeslots", [TimeSlotsController::class, "read"]);
$app->router->get("/timeslots/add", [TimeSlotsController::class, "create"]);
$app->router->post("/timeslots/edit", [TimeSlotsController::class, "edit"]);
$app->router->post("/timeslots/add", [TimeSlotsController::class, "create"]);
$app->router->post("/timeslots/delete", [TimeSlotsController::class, "delete"]);

// /coupon
$app->router->get("/coupons", [CouponController::class, "getCoupons"]);
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
$app->router->get("/codelist", [CountryCodeController::class, "getCodelist"]);
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
$app->router->get("/payment", [PaymentController::class, "getPayment"]);
$app->router->get("/paymentlist", [PaymentController::class, "read"]);
$app->router->post("/paymentlist/edit", [PaymentController::class, "update"]);

// /app
$app->router->post("/app/assigndeliveryboy", [AppController::class, "assignDeliveryBoy"]);
$app->router->post("/app/riderres", [AppController::class, "deliveryBoyNotiRes"]);
$app->router->post("/app/assignedorders", [AppController::class, "assignedOrders"]);
$app->router->get("/app/orders", [AppController::class, "pendingOrders"]);
$app->router->get("/app/activeorders", [AppController::class, "activeOrders"]);
$app->router->post("/app/ordercomplete", [AppController::class, "completeOrder"]);

// /customers
$app->router->get("/customers", [CustomersController::class, 'readCustomers']);
$app->router->get("/customers/feedback", [CustomersController::class, 'readFeedback']);

// /deliveryboys
$app->router->get("/deliveryboys/add", [DeliveryBoysController::class, "create"]);
$app->router->post("/deliveryboys/add", [DeliveryBoysController::class, "create"]);
$app->router->get("/deliveryboys", [DeliveryBoysController::class, "read"]);
$app->router->post("/deliveryboys/delete", [DeliveryBoysController::class, "delete"]);
$app->router->post("/deliveryboys/update", [DeliveryBoysController::class, "update"]);

// /productrequest
$app->router->get("/productrequest", [ProductRequestController::class, "read"]);

// /bookings
$app->router->get("/bookings", [ProductRequestController::class, "readBookings"]);

// /settings
$app->router->get("/api/settings", [SettingsController::class, "read"]);

// /riderorders
$app->router->get("/riderorders", [RiderOrdersController::class, "read"]);

// /banner
$app->router->get("/bannerlist", [BannerController::class, "read"]);
$app->router->get("/banner", [BannerController::class, "getBanner"]);
$app->router->get("/bannerlist/add", [BannerController::class, "create"]);
$app->router->post("/bannerlist/add", [BannerController::class, "create"]);
$app->router->post("/bannerlist/update", [BannerController::class, "update"]);
$app->router->post("/bannerlist/delete", [BannerController::class, "delete"]);

// /stepsinfo
$app->router->get("/stepsinfo", [StepsInfoController::class, "read"]);
$app->router->get("/stepsinfo/add", [StepsInfoController::class, "add"]);
$app->router->post("/stepsinfo/add", [StepsInfoController::class, "add"]);
$app->router->post("/stepsinfo/update", [StepsInfoController::class, "update"]);
$app->router->post("/stepsinfo/delete", [StepsInfoController::class, "delete"]);
$app->router->get("/getstepsinfo", [StepsInfoController::class, "getstepsinfo"]);

// /subproducts
$app->router->get("/subproducts", [SubProductsController::class, "read"]);
$app->router->get("/subproducts/add", [SubProductsController::class, "add"]);
$app->router->post("/subproducts/add", [SubProductsController::class, "add"]);
$app->router->post("/subproducts/update", [SubProductsController::class, "update"]);
$app->router->post("/subproducts/delete", [SubProductsController::class, "delete"]);
$app->router->get("/getsubproduct", [SubProductsController::class, "getSubProduct"]);

$app->run();
