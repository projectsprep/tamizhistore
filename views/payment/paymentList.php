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
            <div class="loadingio-eclipse" id="loader">
                <div class="ldio-rpinwye8j0b">
                    <div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none;" id="display">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap table-hover" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Payment Gateway Name</th>
                                            <th scope="col">Payment Gateway Image</th>
                                            <th scope="col">Credentials Title</th>
                                            <th scope="col">Credentials value</th>
                                            <th scope="col">Status</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $title; ?></h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= $img; ?></h5>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $cred_title ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $cred_value ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $status ? "Active" : "Deactive" ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" id="<?= $id ?>" class="editpayment"><i class="bx bx-edit"></i></a>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>

<div class="modal fade hide" role="dialog" id="editPayment" aria-labelledby="editPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/paymentlist/edit" method="Post" id="editPaymentForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit category</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productname">Payment Gateway Name</label>
                        <input type="text" class="form-control" required name="gateway" id="gateway" />
                        <div class="invalid-feedback">
                            Please enter a valid Payment Gateway Name
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Credentials Title</label>
                        <input type="text" class="form-control" required name="title" id="title" />
                        <div class="invalid-feedback">
                            Please enter a valid Credential Title
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Credentials Value</label>
                        <input type="text" class="form-control" required name="value" id="value" />
                        <div class="invalid-feedback">
                            Please enter a valid Credential Value
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Select Status</label>
                        <select class="form-select" required="" name="status" id="paymentStatus">
                            <option selected="" value=""></option>
                            <option value="1">Publish</option>
                            <option value="0">Unpublish</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid Category.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="paymentid">
                    <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    @keyframes ldio-rpinwye8j0b {
        0% {
            transform: rotate(0deg)
        }

        50% {
            transform: rotate(180deg)
        }

        100% {
            transform: rotate(360deg)
        }
    }

    .ldio-rpinwye8j0b div {
        position: absolute;
        animation: ldio-rpinwye8j0b 1s linear infinite;
        width: 160px;
        height: 160px;
        top: 20px;
        left: 20px;
        border-radius: 50%;
        box-shadow: 0 4px 0 0 #2a3042;
        transform-origin: 80px 82px;
    }

    .loadingio-eclipse {
        margin: auto;
        margin-top: 18%;
        margin-bottom: auto;
        width: 200px;
        height: 200px;
        overflow: hidden;
    }

    .ldio-rpinwye8j0b {
        width: 100%;
        height: 100%;
        position: relative;
        transform: translateZ(0) scale(1);
        backface-visibility: hidden;
        transform-origin: 0 0;
        /* see note above */
    }

    .ldio-rpinwye8j0b div {
        box-sizing: content-box;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $("#datatable").DataTable();
    $(document).ready(
        function() {
            $(document).on("click", ".editpayment", function() {
                var paymentid = $(this).attr('id');
                $("#paymentid").val(paymentid);

                $.ajax({
                    url: "/api/payment?id=" + paymentid,
                    method: "POST",
                    data: {},
                    dataType: "json",
                    success: function(data) {
                        $("#editPayment").modal("show");
                        $("#gateway").val(data[0].title);
                        $("#title").val(data[0].cred_title);
                        $("#value").val(data[0].cred_value);
                        $("#paymentStatus").find(":selected").text(data[0].status == 1 ? "Publish" : "Unpublish");
                        $("#paymentStatus").find(":selected").val(data[0].status);

                    }
                })

            })

            $.ajax({
                url: "/paymentlist",
                method: "GET",
                data: {
                    view: ""
                },
                beforeSend: function() {
                    $("#loader").show();
                },
                success: function(data) {
                    $("#loader").hide();
                    $("#display").show();
                },
            })

            $(document).on("click", ".deleteTime", function() {
                console.log("Yes");
                var id = $(this).attr("id");
                swal({
                        title: "Are you sure?",
                        text: "Once deleted cannot be retrived.",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "/timeslots/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Area deleted successfully!", {
                                        icon: "success",
                                    }).then((value) => {
                                        location.reload();
                                    })
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1500)
                                }
                            })

                        } else {
                            swal("Your Timeslot is safe!");
                        }
                    })
            })

            $("#category").on("change", function() {
                var id = $(this).val();
                // console.log(id);
                $.ajax({
                    url: "api/getsubcategorynames",
                    method: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#subCategory").html(data);
                    },
                })
            })

        }
    )
</script>