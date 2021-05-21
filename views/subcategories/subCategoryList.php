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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap table-striped table-hover" style="display: none;" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">SubCategory Name</th>
                                            <th scope="col">SubCategory Image</th>
                                            <th scope="col">Category Name</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $name; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <img src="<?= $img; ?>" class="img-thumbnail" alt="">
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $catname ?></h5>
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
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="subcategoryid">
                    <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                    url: "/api/subcategory",
                    method: "POST",
                    data: {id: subcategoryid},
                    dataType: "json",
                    success: function(data) {
                        $("#editModal").modal("show");
                        $("#subcategoryid").val(subcategoryid);
                        $("#subcategoryname").val(data[0].name);
                        $("#category").find(":selected").text(data[0].catname);
                        $("#category").find(":selected").val(data[0].cat_id);
                    }
                })
            })

            $.ajax({
                    url: "/api/getcategorynames",
                    method: "POST",
                    dataType: "json",
                    success: function(data){
                        $("#category").append(data);
                        console.log(data);
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
                    $("#datatable").show();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                },
            })

            $(document).on("click", ".deletecategory", function() {
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
                                url: "/categorylist/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Category deleted successfully!", {
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
                            swal("Your category is safe!");
                        }
                    })
            })

        }
    )
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css
">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
 -->
<!-- <style>
 .paginate_button{background-color: #556ee6 !important;}
</style> -->