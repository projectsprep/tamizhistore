<?php
use app\models\DB;

$db = new DB();
$conn = $db->conn();

?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
        <?php
if(isset($_GET['msg'])){
?>
        <div class="alert alert-primary alert-dismissible fade show">
            <?php echo $_GET['msg'];?>
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
                            <table class="table align-middle table-nowrap table-hover mydatatable" id="dataTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 70px;">Sl.no</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Category Image</th>
                                        <th scope="col">Total Subcategory</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="categorylists">
                                    <tr rowspan="5"><td colspan="5" align="center">
                                        <div class="spinner-border" role="status" id="loader">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="pagination pagination-rounded justify-content-center mt-4">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
</div>
                <!-- End Page-content -->

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

<script src="/assets/libs/jquery/jquery.min.js"></script>
<script>
$(document).ready(function(){
    loadCategoryList()
    function loadCategoryList(){
        $.ajax({
            url: "api/getcategory",
            method:"GET",
            data:{view:""},
            dataType: "json",
            beforeSend: function(){
                $("#loader").show();
            },
            success: function(data){
                setTimeout(function(){
                    $("#loader").hide();
                    $("#categorylists").html(data);
                }, 1300);
            },
            error: function(xhr, ajaxOptions, thrownError){
                    alert(thrownError);
                },
        })
    }


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
        // event.preventDefault();
        if($("#categoryname").val() == ""){
            $("#editModal").modal("hide");
            $(".container-fluid").prepend("<div class='alert alert-danger alert-dismissible fade show'>All fields are required <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
        }else{
            $.ajax({
                url: "/categorylist/edit",
                method: "POST",
                data: $("#editCategoryForm").serialize(),
                success: function(data){
                    $("#editModal").modal("hide");
                    loadCategoryList();
                    $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                },
                error: function(xhr, ajaxOptions, thrownError){
                    $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                    alert("something happened");
                },
            })
        }
    })
});
</script>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(".mydatatable").DataTable();
</script>