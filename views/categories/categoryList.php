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
                        <div class="spinner-border" role="status" id="loader" >
                                    <span class="sr-only">Loading...</span>
                                </div>
                            <div class="table-responsive">
                                <table class="table table-nowrap table-hover dt-responsive" style="display: none;" id="datatable">
                                    <thead class="table-light thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 70px;">Sl.no</th>
                                            <th scope="col">Category Name</th>
                                            <th scope="col">Category Image</th>
                                            <th scope="col">Total Subcategory</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $catname; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <img src="<?= $catimg; ?>" class="img-thumbnail" style="max-width:65%" alt="">
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $conn->query("select * from subcategory where cat_id = $id;")->num_rows; ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <a href="#" id=<?= $id ?> class="text-danger deletecategory"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id=<?= $id ?> class="editcategory"><i class="bx bx-edit"></i></a>
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
            <form action="/categorylist/edit" method="Post" id="editCategoryForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit category</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Category Name</label>
                        <input type="text" name="categoryName" id="categoryname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Category Image</label>
                        <input type="file" name="categoryimage" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="categoryid">
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
    function(){
        $(document).on("click", ".editcategory", function(){
            var categoryid = $(this).attr('id');
            $("#categoryid").val(categoryid);

            $.ajax({
                url: "/api/category?id="+categoryid,
                method:"GET",
                data: {},
                dataType: "json",
                success: function(data){
                    $("#editModal").modal("show");
                    $("#categoryid").val(categoryid);
                    $("#categoryname").val(data[0].catname)
                }
            })
        })

        $("#editCategoryForm").on("submit", function(){
        if($("#categoryname").val() == ""){
            event.preventDefault();
            $("#editModal").modal("hide");
            $(".container-fluid").prepend("<div class='alert alert-danger alert-dismissible fade show'>All fields are required <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
        }else{
            $.ajax({
                url: "/categorylist/edit",
                method: "POST",
                data: $("#editCategoryForm").serialize(),
                success: function(data){
                    $("#editModal").modal("hide");
                    $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert("something happened");
                },
            })
        }
    })

    $.ajax({
            url: "/categorylist",
            method:"GET",
            data:{view:""},
            beforeSend: function(){
                $("#loader").show();
            },
            success: function(data){
                $("#loader").hide();
                $("#datatable").show();
            },
            error: function(xhr, ajaxOptions, thrownError){
                    alert(thrownError);
                },
        })

    $(document).on("click", ".deletecategory", function(){
        var id = $(this).attr("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted cannot be retrived.",
            icon: "warning",
            buttons: true,
            dangerMode: true
        })
        .then((willDelete)=>{
            if(willDelete){
                $.ajax({
                    url: "/categorylist/delete",
                    method: "POST",
                    data: {id: id},
                    success: function(){
                        swal("Category deleted successfully!", {
                            icon: "success",
                        }).then((value)=>{
                            location.reload();
                        })
                        setTimeout(function(){
                            location.reload();
                        }, 1500)
                    }
                })
                
            }else{
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