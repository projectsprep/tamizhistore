<?php

use app\models\ProductsModel;
use app\models\DB;

$db = new DB();
$db = $db->conn();
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
                                <table class="table table-nowrap table-hover table-striped" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Product Image</th>
                                            <th scope="col">Seller name</th>
                                            <th scope="col">Subcategory Name</th>
                                            <th scope="col">MinTime</th>
                                            <th scope="col">MaxTime</th>
                                            <th scope="col">Pincode</th>
                                            <th scope="col">Product Price</th>
                                            <th scope="col">Product Range</th>
                                            <th scope="col">In Stock</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $pname; ?></h5>
                                                </td>
                                                <td>
                                                    <img src="<?= $pimg; ?>" class="img-thumbnail rounded" alt="">
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $sname; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $name; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= date("h:i a", strtotime($minTime)); ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= date("h:i a", strtotime($maxTime)); ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $pincode; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $pprice; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $pgms; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $stock ? "Yes" : "No" ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $status ? "Published" : "Unpublished" ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="text-danger deleteproduct" id="<?= $id ?>"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id="<?= $id ?>" class="editproduct"><i class="bx bx-edit"></i></a>
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
            <form action="/productlist/edit" method="Post" id="editProductForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit Food Item</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" required name="productName" id="productName" />
                    </div>
                    <div class="form-group">
                        <label for="">Product Image (optional)</label>
                        <input type="file" name="productimage" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seller Name / Shop Name</label>
                        <div>
                            <input type="text" id="sellerName" class="form-control" required name="sellerName" />
                        </div>
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
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Select Subcategory</label>
                        <select class="form-select" required="" name="subCategory" id="subCategory">
                            <option selected="" value=""></option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid Subcategory.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="minTime">Min Avilable Hours</label>
                            <input type="time" id="minTime" class="form-control" required name="minTime" />
                            <div class="invalid-feedback">
                                Please enter a valid Min time
                            </div>
                        </div>

                        <div class="col mb-3">
                            <label for="maxTime">Max Available Hours</label>
                            <input type="time" id="maxTime" class="form-control" required name="maxTime" />
                            <div class="invalid-feedback">
                                Please enter a valid Max time
                            </div>
                        </div>
                        <h6>(Leave the time blank or at 00:00 to make product available all the time.)</h6>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pincode</label>
                        <div>
                            <input type="number" id="pincode" class="form-control" required name="pincode" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Description</label>
                        <div>
                            <textarea required class="form-control" rows="3" name="description" id="description"></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">In Stock?</label>
                        <select class="form-select" required="" name="outofstock" id="stock">
                            <option selected="" value=""></option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Product publish or unpublish?</label>
                        <select class="form-select" required="" id="productStatus" name="publish">
                            <option selected="" value=""></option>
                            <option value="0">Unpublish</option>
                            <option value="1">Publish</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Make product popular?</label>
                        <select class="form-select" required="" id="popular" name="popular">
                            <option selected="" value=""></option>
                            <option value="0">Unpopular</option>
                            <option value="1">Popular</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Price (<i class="bx bx-rupee"></i>)</label>
                        <div>
                            <input type="number" id="productPrice" class="form-control" required name="price" value="<?= $pprice ?>" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product (GMS,KG,LTR,ML,PCS..)</label>
                        <input type="text" name="range" id="productRange" class="form-control">
                        <div class="invalid-feedback">
                            Please select a valid option.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Discount (in digits)</label>
                        <div>
                            <input type="number" class="form-control" id="discount" name="discount" min='0' max="100" />
                        </div>
                        <div class="invalid-feedback">
                            Please enter a valid Product Discount.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="productid">
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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $("#datatable").DataTable();
    $(document).ready(
        function() {

            $(document).on("click", ".editproduct", function() {
                var productid = $(this).attr('id');
                $("#productid").val(productid);

                $.ajax({
                    url: "/getproduct?id="+productid,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#editModal").modal("show");
                        $("#productid").val(productid);
                        $("#productName").val(data[0].pname);
                        $("#sellerName").val(data[0].sname);
                        $("#productPrice").val(data[0].pprice);
                        $("#productRange").val(data[0].pgms);
                        $("#category").find(":selected").text(data[0].catname);
                        $("#category").find(":selected").val(data[0].cid);
                        $("#subCategory").find(":selected").text(data[0].subname);
                        $("#subCategory").find(":selected").val(data[0].sid);
                        $("#minTime").val(data[0].minTime);
                        $("#maxTime").val(data[0].maxTime);
                        $("#pincode").val(data[0].pincode);
                        $("#stock").find(":selected").val(data[0].stock);
                        $("#stock").find(":selected").text(data[0].stock == 1 ? "Yes" : "No");
                        $("#productStatus").find(":selected").text(data[0].status == 1 ? "Publish" : "Unpublish");
                        $("#productStatus").find(":selected").val(data[0].status);
                        $("#popular").find(":selected").text(data[0].popular == 1 ? "Popular" : "Unpopular");
                        $("#popular").find(":selected").val(data[0].popular);
                        $("#description").val(data[0].psdesc);
                        $("#discount").val(data[0].discount);
                    }
                    // error: function(data, thrownError, ajaxOptions){
                    //     alert(ajaxOptions)
                    // }
                })

                $.ajax({
                    url: "/getcategorynames",
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#category").append(data);
                        // console.log(data);
                        var id = $("#category").find(":selected").val();
                        // console.log(id);
                        $.ajax({
                            url: "/getsubcategorynames?id="+id,
                            method: "GET",
                            dataType: "json",
                            success: function(data) {
                                $("#subCategory").append(data);
                            },
                        })
                    },
                })

            })

            $.ajax({
                url: "/productlist",
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

            $(document).on("click", ".deleteproduct", function() {
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
                                url: "/productlist/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Product deleted successfully!", {
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
                            swal("Your product is safe!");
                        }
                    })
            })

            $("#category").on("change", function() {
                var id = $(this).val();
                // console.log(id);
                $.ajax({
                    url: "/getsubcategorynames?id="+id,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#subCategory").html(data);
                    },
                })
            })

        }
    )
</script>