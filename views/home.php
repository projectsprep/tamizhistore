<?php
foreach ($params as $param) {
    foreach ($param as $key => $value) {
        $$key = $value;
    }
}
?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <?php
            if (isset($_GET['msg'])) {
            ?>
                <div class="alert alert-primary alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            ?>
            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden shadow">
                        <div class="bg-primary bg-soft">
                            <div class="row">

                                <div class="col-12">
                                    <img src="/assets/images/adminpage.jpg" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="avatar-lg profile-user-wid mb-1">
                                        <img src="/assets/images/firsticon.png" alt="" class="img-thumbnail rounded-circle">
                                    </div>
                                    <h5 class="font-size-15 text-truncate">Tamizhistore</h5>
                                    <p class="text-muted mb-0 text-truncate">Admin</p>
                                </div>

                                <div class="col-xl-6">
                                    <div class="pt-4">

                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="font-size-15">125</h5>
                                                <p class="text-muted mb-0">Projects</p>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="font-size-15">$1245</h5>
                                                <p class="text-muted mb-0">Revenue</p>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="/profile" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Monthly Earning</h4>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="text-muted">This month</p>
                                                <h3>$34,252</h3>
                                                <p class="text-muted"><span class="text-success me-2"> 12% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>

                                                <div class="mt-4">
                                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mt-4 mt-sm-0">
                                                    <div id="radialBar-chart" class="apex-charts"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0">We craft digital, graphic and dimensional thinking.</p>
                                    </div>
                                </div>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/categorylist"><div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Category</p>
                                            <h4 class="mb-0"><?= $category ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-list-ol font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/subcategorylist">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Subcategory</p>
                                            <h4 class="mb-0"><?= $subcategory ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-list-ul font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/productlist">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Product</p>
                                            <h4 class="mb-0"><?= $product ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-cube font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/couponlist">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Coupon</p>
                                            <h4 class="mb-0"><?= $tbl_coupon ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-gift font-size-24"></i>
                                            </span>
                                        </div>
                                    </div></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/arealist">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Area</p>
                                            <h4 class="mb-0"><?= $area_db ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-map font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/timeslots">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Timeslots</p>
                                            <h4 class="mb-0"><?= $timeslot ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-time-five font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/customers/feedback">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Feedback</p>
                                            <h4 class="mb-0"><?= $feedback ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-copy-alt font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/countrycode">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Country Code</p>
                                            <h4 class="mb-0"><?= $code ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-cart-alt font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/customers">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Customers</p>
                                            <h4 class="mb-0"><?= $user ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-user-plus font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid shadow">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted fw-medium">Feedback</p>
                                                        <h4 class="mb-0"><?= $feedback ?></h4>
                                                    </div>

                                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                        <span class="avatar-title">
                                                            <i class="bx bx-copy-alt font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid shadow">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted fw-medium">Order</p>
                                                        <h4 class="mb-0">$35, 723</h4>
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                            <i class="bx bx-archive-in font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid shadow">
                                            <div class="card-body">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p class="text-muted fw-medium">Customers</p>
                                                        <h4 class="mb-0">$16.2</h4>
                                                    </div>

                                                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                            <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/orders">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Total Orders</p>
                                            <h4 class="mb-0"><?= $orders ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-time-five font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/orders">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Pending Orders</p>
                                            <h4 class="mb-0"><?= $pending ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-list-ol font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/orders">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Canceled Orders</p>
                                            <h4 class="mb-0"><?= $cancelled ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-list-ol font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/notifications">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Notifications</p>
                                            <h4 class="mb-0"><?= $noti ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-gift font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <a href="/customers/feedback">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Customer Rating</p>
                                            <h4 class="mb-0"><?= $rate_order ?></h4>
                                        </div>

                                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-map font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid shadow">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="text-muted fw-medium">Delivery Boys</p>
                                            <h4 class="mb-0"><?= $rider ?></h4>
                                        </div>

                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                            <span class="avatar-title">
                                                <i class="bx bx-list-ol font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="rightbar-overlay"></div>

    </body>


    </html>