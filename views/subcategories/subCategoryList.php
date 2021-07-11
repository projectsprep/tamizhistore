<?php

use app\models\DB;

$db = new DB();
$conn = $db->conn();

?>
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
                                <table class="table table-nowrap table-striped table-hover" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">SubCategory ID</th>
                                            <th scope="col">SubCategory Name</th>
                                            <th scope="col">SubCategory Image</th>
                                            <th scope="col">Category Name</th>
                                            <th scope="col">Shop Address</th>
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
                                                <td align="center">
                                                    <span class="">
                                                        <?= $i++; ?>
                                                    </span>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $id; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $name; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <img src="/assets/images/<?= $img; ?>" class="img-thumbnail" alt="">
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $catname ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1">#<?= str_replace("|", ",", $address) ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $deliverycharge; ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $status == 1 ? "Active" : "Inactive"; ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <a href="#" id=<?= $id ?> class="text-danger deletesubcategory"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id=<?= $id ?> class="editsubcategory"><i class="bx bx-edit"></i></a>
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


<div class="modal fade hide" role="dialog" id="editModal" aria-labelledby="editModalLabel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/subcategorylist/edit" method="Post" id="editCategoryForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit SubCategory</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Subcategory Name</label>
                        <input type="text" name="subcategoryName" id="subcategoryname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Subcategory Image (optional)</label>
                        <input type="file" name="subcategoryimage" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Select Category</label>
                        <select class="form-select" required="" name="category" id="category">
                            <option selected="" value=""></option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid Category.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Shop DoorNo.</label>
                        <input type="text" name="doorno" id="doorno" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Shop AddressLine 1</label>
                        <input type="text" name="addr1" id="addr1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Shop AddressLine 2</label>
                        <input type="text" name="addr2" id="addr2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Shop Pincode</label>
                        <input type="number" name="pincode" id="pincode" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Delivery Charge</label>
                        <input type="number" name="charge" id="charge" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Shop status</label>
                        <select class="form-select" required name="shopstatus" id="shopstatus">
                            <option value="" selected></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid Status.
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="subcategoryid">
                    <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-hidden="true">Close</button>
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

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $("#datatable").DataTable();
</script>

<script>
    $(document).ready(
        function() {
            $(document).on("click", ".editsubcategory", function() {
                var subcategoryid = $(this).attr('id');
                $("#subcategoryid").val(subcategoryid);

                $.ajax({
                    url: "/subcategory?id="+subcategoryid,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#editModal").modal("show");
                        $("#subcategoryid").val(subcategoryid);
                        $("#subcategoryname").val(data[0].name);
                        $("#category").find(":selected").text(data[0].catname);
                        $("#category").find(":selected").val(data[0].cat_id);
                        $("#shopstatus").find(":selected").text(data[0].status == 1 ? "Active" : "Inactive");
                        $("#shopstatus").find(":selected").val(data[0].status);
                        $("#charge").val(data[0].deliverycharge)
                        let address = data[0].address.split("|");
                        $("#doorno").val(address[0])
                        $("#addr1").val(address[1])
                        $("#addr2").val(address[2])
                        $("#pincode").val(address[3])
                    }
                })
            })

            $.ajax({
                url: "/getcategorynames",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $("#category").append(data);
                },
            })

            $.ajax({
                url: "/subcategorylist",
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
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                },
            })

            $(document).on("click", ".deletesubcategory", function() {
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
                                url: "/subcategorylist/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Subcategory deleted successfully!", {
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
                            swal("Your Subcategory is safe!");
                        }
                    })
            })

        }
    )
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>