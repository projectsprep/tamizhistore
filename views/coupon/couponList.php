<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <?php
            if (isset($_GET['msg'])) {
            ?>
                <div class="alert alert-primary alert-dismissible fade show">
                    <?php echo $_GET['msg']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap table-hover table-striped" style="display: none;" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Coupon Value</th>
                                            <th scope="col">Coupon Image</th>
                                            <th scope="col">Coupon Discount</th>
                                            <th scope="col">Coupon Expiry Date</th>
                                            <th scope="col">Coupon Order Min Value</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($params as $param) {
                                            foreach ($param as $key => $value) {
                                                $$key = $value;
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <span class="">
                                                        <?= $i++; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= $c_title; ?></h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= $c_img; ?></h5>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $c_value ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $cdate ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $min_amt ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" id="<?= $id ?>" class="editcoupon"><i class="bx bx-edit"></i></a>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <div class="spinner-border" role="status" id="loader">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>

<div class="modal fade hide" role="dialog" id="editCoupon" aria-labelledby="editCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/couponlist/edit" method="Post" id="editCouponFrom" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit Coupon</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Coupon Expiry Date</label>
                        <input type="date" class="form-control" required name="expiryDate" id="expiry" />
                        <div class="invalid-feedback">
                            Please enter a valid Date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" class="form-control" required name="couponCode" id="code" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Title</label>
                        <input type="text" class="form-control" required name="couponTitle" id="title" />
                        <div class="invalid-feedback">
                            Please enter a valid title.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Status</label>
                        <select class="form-select" required name="couponStatus" id="couponstatus">
                            <option selected="" value=""></option>
                            <option value="publish">Publish</option>
                            <option value="unpublish">Unpublish</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid coupon status.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Min Order Amount</label>
                        <input type="Number" class="form-control" required name="minAmt" id="minAmt" />
                        <div class="invalid-feedback">
                            Please enter a valid Date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Value</label>
                        <input type="Number" class="form-control" required name="discount" id="value" />
                        <div class="invalid-feedback">
                            Please enter a valid Date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coupon Description</label>
                        <div>
                            <textarea required class="form-control" rows="3" name="description" id="description"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="couponid">
                    <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $("#datatable").DataTable();
    $(document).ready(
        function() {
            $(document).on("click", ".editcoupon", function() {
                var couponid = $(this).attr('id');
                $("#couponid").val(couponid);

                $.ajax({
                    url: "/api/coupons?id=" + couponid,
                    method: "POST",
                    data: {},
                    dataType: "json",
                    success: function(data) {
                        $("#editCoupon").modal("show");
                        $("#expiry").val(data[0].cdate);
                        $("#description").val(data[0].c_desc);
                        $("#code").val(data[0].c_title);
                        $("#title").val(data[0].ctitle);
                        $("#couponstatus").find(":selected").text(data[0].status == 1 ? "Publish" : "Unpublish");
                        $("#couponstatus").find(":selected").val(data[0].status);
                        $("#minAmt").val(data[0].min_amt);
                        $("#value").val(data[0].c_value);
                    }
                })
            })

            // $("#editCouponFrom").on("submit", function() {
            //     if ($("#couponCode").val() == "") {
            //         event.preventDefault();
            //         $("#editModal").modal("hide");
            //         $(".container-fluid").prepend("<div class='alert alert-danger alert-dismissible fade show'>All fields are required <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
            //     } else {
            //         $.ajax({
            //             url: "/couponlist",
            //             method: "POST",
            //             data: $("#editCouponFrom").serialize(),
            //             success: function(data) {
            //                 $("#editModal").modal("hide");
            //                 $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
            //             },
            //             error: function(xhr, ajaxOptions, thrownError) {
            //                 alert(ajaxOptions);
            //             },
            //         })
            //     }
            // })

            $.ajax({
                url: "/couponlist",
                method: "GET",
                data: {
                    view: ""
                },
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                    $("#loader").hide();
                    $("#datatable").show();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                },
            })

        }
    )
</script>