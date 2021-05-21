<?php

use app\models\NotificationsModel;

$uriTitle = $_SERVER['REQUEST_URI'] == "/" ? "Dashboard" : ucwords(ltrim($_SERVER['REQUEST_URI'], "/"));

$notify = new NotificationsModel();
$json = $notify->pushedNotifies("noti");
$array = json_decode($json, true);
$_SESSION['notify'] = false;

if($_SESSION['notify'] === true){
    ?>
    <script>$("audio")[0].play();</script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <title><?= $uriTitle ?> - Admin & <?= $uriTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" /> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="shortcut icon" href="/assets/images/favicon.ico" />
    <link href="/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Ubuntu', sans-serif;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-style: none;
        }

        table.dataTable thead th {
            position: relative;
            background-image: none !important;
        }

        .img-thumbnail{
            height: 94px;
        }
    </style>
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="/" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="/assets/images/logo.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="/assets/images/logo-dark.png" alt="" height="17">
                            </span>
                        </a>

                        <a href="/" class="logo logo-light">
                            <span class="logo-lg">
                                <img src="/assets/images/firsticon.png" alt="" height="32">
                                <span class="navbar-brand text-white">TamizhiStore</span>
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="bx bx-search-alt"></span>
                        </div>
                    </form>

                </div>
                <div class="d-flex">

                    <div class="dropdown d-inline-block d-lg-none ms-2">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">

                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="bx bx-fullscreen"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block notifydropdown">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false" onclick="clicked()">
                                <i class="bx bx-bell bx-tada"></i>
                                <span class="badge bg-danger rounded-pill"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0" key="t-notifications"> Notifications </h6>
                                        </div>
                                    </div>
                                </div>
                                <audio id="notifyaudio">
                                    <source src="/assets/notification.mp3" type="audio/mp3">
                                    Your browser does not support audio element
                                </audio>
                                <div data-simplebar style="max-height: 230px;">
                                    <div id="notify"></div>
                                </div>
                                <div class="p-2 border-top d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 text-center" href="/notifications">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span>
                                    </a>
                                </div>
                            </div>
                    </div>
                    <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="/assets/images/firsticon.png" alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-1" key="t-henry">Tamizhistore</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="/profile"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="/logout"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                    </div>
                </div>
                </div>

            </div>
    </div>
    </header>
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title" key="t-menu">Menu</li>

                    <li>
                        <a href="/" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span key="t-dashboards">Dashboards</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span key="t-ecommerce"><span>Category</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/categorylist/add" key="t-products">Add Category</a></li>
                            <li><a href="/categorylist" key="t-product-detail">Category List</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span key="t-ecommerce"><span>Sub Category</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/categorylist/add" key="t-products">Add SubCategory</a></li>
                            <li><a href="/subcategorylist" key="t-product-detail">SubCategory List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cube"></i>
                            <span key="t-ecommerce"><span>Product</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/productlist/add" key="t-products">Add Product</a></li>
                            <li><a href="/productlist/import" key="t-product-detail">Import Product</a></li>
                            <li><a href="/productlist" key="t-orders">Product List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-gift"></i>
                            <span key="t-ecommerce"><span>Coupon</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/couponlist/add" key="t-products">Add Coupon</a></li>
                            <li><a href="/couponlist" key="t-product-detail">Coupon List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-map"></i>
                            <span key="t-ecommerce"><span>Area</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/arealist/add" key="t-products">Add Area</a></li>
                            <li><a href="/arealist" key="t-orders">Area List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-time-five"></i>
                            <span key="t-ecommerce"><span>Timeslots</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/timeslots/add" key="t-products">Add Timeslot</a></li>
                            <li><a href="/timeslots" key="t-product-detail">Timeslot List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-bell"></i>
                            <span key="t-ecommerce"><span>Notification</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/notifications/add" key="t-products">Add Notification</a></li>
                            <li><a href="/notifications" key="t-orders">Notification List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cart-alt"></i>
                            <span key="t-ecommerce"><span>Order</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/orders" key="t-product-detail">Total Order</a></li>
                            <li><a href="/orders/pending" key="t-products">Pending Order</a></li>
                            <li><a href="ecommerce-orders.html" key="t-orders">Export Order</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-user-detail"></i>
                            <span key="t-ecommerce"><span>Customer Section</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="ecommerce-products.html" key="t-products">Customers</a></li>
                            <li><a href="ecommerce-product-detail.html" key="t-product-detail">Customer Rating</a></li>
                            <li><a href="ecommerce-orders.html" key="t-orders">Feedback</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-bell"></i>
                            <span key="t-ecommerce"><span>Country Code</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="/countrycode/add" key="t-products">Add Country Code</a></li>
                            <li><a href="/countrycode" key="t-orders">Country Code List</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="/paymentlist" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span key="t-chat">Payment List</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{content}}

</body>

</html>

<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="/assets/js/pages/dashboard.init.js"></script>
<script src="/assets/js/app.js"></script>
<!-- <script src="/assets/js/notify.php"></script> -->
<script src="/assets/libs/dropzone/min/dropzone.min.js"></script>
<script>
    $(document).ready(function() {
        $(".alert").delay(2000).slideUp(300, function() {
        $(this).alert('close');
    });
        function loadUnseenNotification(view = "") {
            $.ajax({
                url: "/api/pushednotifies",
                method: "POST",
                data: {
                    view: view
                },
                dataType: "json",
                success: function(data) {
                    $("#notify").html(data.notification);
                    if (data.unseenNotification > 0) {
                        // $("audio")[0].muted = false;
                        <?php
                            $_SESSION['notify'] = true;
                        ?>
                        $(".badge").html(data.unseenNotification);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(data);
                },
            })
        }

        loadUnseenNotification()
        $(".dropdown.d-inline-block.notifydropdown").on('shown.bs.dropdown', function() {
            $(".badge").html('');
            loadUnseenNotification("yes");
        });
        setInterval(function() {
            loadUnseenNotification();
        }, 1000 * 60 * 2);
    })
</script>