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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="spinner-border" role="status" id="loader">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover" style="display: none;" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Product Image</th>
                                            <th scope="col">Seller name</th>
                                            <th scope="col">Category Name</th>
                                            <th scope="col">Subcategory Name</th>
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
                                                        <h5 class="font-size-14 mb-1"><?= $catname; ?></h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $name; ?></h5>
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
                    <h4 class="modal-title">Edit category</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" required name="productName" id="productName" />
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
                            <option value=""><?= $stock == '1' ? 'No' : 'Yes' ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Product publish or unpublish?</label>
                        <select class="form-select" required="" id="productStatus" name="publish">
                            <option selected="" value=""></option>
                            <option value="<?= $status == '1' ? 'unpublish' : 'publish' ?>"><?= $status == '1' ? 'Unpublish' : 'Publish' ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="validationCustom03" class="form-label">Make product popular?</label>
                        <select class="form-select" required="" id="popular" name="popular">
                            <option selected="" value=""></option>
                            <option value="<?= $status == '1' ? '0' : '1' ?>"><?= $status == '1' ? 'No' : 'Yes' ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Price (<i class="bx bx-rupee"></i>)</label>
                        <div>
                            <input type="text" id="productPrice" class="form-control" required name="price" value="<?= $pprice ?>" />
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
                            <input type="number" class="form-control" id="discount" name="discount" min='0' max="100"/>
                        </div>
                        <div class="invalid-feedback">
                            Please enter a valid Product Discount.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="productid">
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
            $(document).on("click", ".editproduct", function() {
                var productid = $(this).attr('id');
                $("#productid").val(productid);

                $.ajax({
                    url: "/api/product?id=" + productid,
                    method: "GET",
                    data: {},
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
                        $("#stock").find(":selected").val(data[0].stock);
                        $("#stock").find(":selected").text(data[0].stock == 1 ? "Yes" : "No");
                        $("#productStatus").find(":selected").text(data[0].status == 1 ? "Publish" : "Unpublish");
                        $("#productStatus").find(":selected").val(data[0].status);
                        $("#popular").find(":selected").text(data[0].popular == 1 ? "Popular" : "Unpopular");
                        $("#popular").find(":selected").val(data[0].popular);
                        $("#description").val(data[0].psdesc);
                        $("#discount").val(data[0].discount);
                    }
                })

                $.ajax({
                    url: "/api/getcategorynames",
                    method: "GET",
                    dataType: "json",
                    success: function(data){
                        $("#category").append(data);
                        // console.log(data);
                        var id = $("#category").find(":selected").val();
                        console.log(id);
                        $.ajax({
                            url: "/api/getsubcategorynames",
                            method: "POST",
                            data: {id: id},
                            dataType: "json",
                            success: function(data){
                                $("#subCategory").append(data);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(id);
                            },
                        })
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                            alert("something happened");
                        },
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
                    $("#datatable").show();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
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

            $("#category").on("change",function(){
                var id = $(this).val();
                // console.log(id);
                $.ajax({
                    url: "api/getsubcategorynames",
                    method: "POST",
                    data: {id: id},
                    dataType: "json",
                    success: function(data){
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