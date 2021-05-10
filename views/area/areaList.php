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
                                            <th scope="col">Area Name</th>
                                            <th scope="col">Delivery Charge</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $name; ?></h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= $dcharge; ?></h5>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $status ? "Published" : "Unpublished" ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="text-danger deleteArea" id="<?= $id ?>"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id="<?= $id ?>" class="editArea"><i class="bx bx-edit"></i></a>
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

<div class="modal fade hide" role="dialog" id="editArea" aria-labelledby="editAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/arealist/edit" method="Post" id="editAreaFrom" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit Area</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productname">Area Name</label>
                        <input type="text" class="form-control" required name="areaName" id="areaName" />
                        <div class="invalid-feedback">
                            Please enter a valid Area name
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Delivery Charge</label>
                        <input type="number" class="form-control" required name="delCharge" id="delCharge" />
                        <div class="invalid-feedback">
                            Please enter a valid Date.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" required name="status" id="areaStatus">
                            <option selected="" value=""></option>
                            <option value="1">Publish</option>
                            <option value="0">Unpublish</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid coupon status.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="areaid">
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
            $(document).on("click", ".editArea", function() {
                var areaid = $(this).attr('id');
                $("#areaid").val(areaid);

                $.ajax({
                    url: "/api/area?id=" + areaid,
                    method: "GET",
                    data: {},
                    dataType: "json",
                    success: function(data) {
                        $("#editArea").modal("show");
                        $("#areaName").val(data[0].name);
                        $("#delCharge").val(data[0].dcharge);
                        $("#areaStatus").find(":selected").text(data[0].status == 1 ? "Publish" : "Unpublish");
                        $("#areaStatus").find(":selected").val(data[0].status);
                    }
                })

            })

            $("#editProductForm").on("submit", function() {
                // console.log("something");
                // if ($("#categoryname").val() == "") {
                //     event.preventDefault();
                //     $("#editModal").modal("hide");
                //     $(".container-fluid").prepend("<div class='alert alert-danger alert-dismissible fade show'>All fields are required <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                // } else {
                //     $.ajax({
                //         url: "/productlist/edit",
                //         method: "POST",
                //         data: $("#editProductForm").serialize(),
                //         success: function(data) {
                //             $("#editModal").modal("hide");
                //             $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                //         },
                //         error: function(xhr, ajaxOptions, thrownError) {
                //             alert("something happened");
                //         },
                //     })
                // }
            })

            $.ajax({
                url: "/arealist",
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

            $(document).on("click", ".deleteArea", function() {
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
                                url: "/arealist/delete",
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
                            swal("Your Arealist is safe!");
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
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError);
                    },
                })
            })

        }
    )
</script>