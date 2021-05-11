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
                                <table class="table table-striped table-nowrap table-hover" style="display: none;" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Country Code</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $ccode; ?></h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= $status ? "Active" : "Inactive"; ?></h5>
                                                </td>
                                                <td>
                                                    <a href="#" class="text-danger deletecode" id="<?= $id ?>"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id="<?= $id ?>" class="editcode"><i class="bx bx-edit"></i></a>
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

<div class="modal fade hide" role="dialog" id="editCode" aria-labelledby="editCodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/countrycode/edit" method="Post" id="editCodeFrom" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit Country Code</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Country Code</label>
                        <input type="text" class="form-control" required name="cc" id="cc" />
                        <div class="invalid-feedback">
                            Please enter a valid code
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Code Status</label>
                        <select class="form-select" required name="codeStatus" id="codeStatus">
                            <option selected="" value=""></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid code status.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="codeid">
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
            $(document).on("click", ".editcode", function() {
                var codeid = $(this).attr('id');
                $("#codeid").val(codeid);

                $.ajax({
                    url: "/api/codelist?id=" + codeid,
                    method: "POST",
                    data: {},
                    dataType: "json",
                    success: function(data) {
                        $("#editCode").modal("show");
                        $("#cc").val(data[0].ccode);
                        $("#codeStatus").find(":selected").text(data[0].status == 1 ? "Active" : "Inactive");
                        $('#codeStatus').find(":selected").val(data[0].status);
                    }
                })
            })

            $(document).on("click", ".deletecode", function() {
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
                                url: "/countrycode/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Country Code deleted successfully!", {
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
                            swal("Your Country Code is safe!");
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
                url: "/countrycode",
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
            })

        }
    )
</script>