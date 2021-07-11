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
                                            <th scope="col">Banner Image</th>
                                            <th scope="col">Message</th>
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
                                                    <img src="<?= $image; ?>" class="img-thumbnail" alt="">
                                                </td>
                                                <td align="center">
                                                    <div>
                                                        <h5 class="font-size-14 mb-1"><?= $message; ?></h5>
                                                    </div>
                                                </td>
                                                <td align="center">
                                                    <a href="#" id=<?= $id ?> class="text-danger deletebanner"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id=<?= $id ?> class="editbanner"><i class="bx bx-edit"></i></a>
                                                </td>

                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <!-- <div class="spinner-border" role="status" id="loader">
                                        <span class="sr-only">Loading...</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade hide" role="dialog" id="editModal" aria-labelledby="editModalLabel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/stepsinfo/update" method="Post" id="editBannerForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Banner</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Banner Message</label>
                        <input type="text" name="msg" id="msg" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Banner Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="bannerid">
                    <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $("#datatable").DataTable();
</script>
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
<script>
    $(document).ready(
        function() {
            $(document).on("click", ".editbanner", function() {
                var bannerid = $(this).attr('id');
                $("#bannerid").val(bannerid);

                $.ajax({
                    url: "/getstepsinfo?id=" + bannerid,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#editModal").modal("show");
                        $("#bannerid").val(bannerid);
                        $("#msg").val(data[0].message);
                    }
                })
            })

            $("#editBannerForm").on("submit", function() {
                if ($("#categoryname").val() == "") {
                    event.preventDefault();
                    $("#editModal").modal("hide");
                    $(".container-fluid").prepend("<div class='alert alert-danger alert-dismissible fade show'>All fields are required <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>");
                } else {
                    $.ajax({
                        url: "/stepsinfo/update",
                        method: "POST",
                        data: $("#editBannerForm").serialize(),
                        success: function(data) {
                            $("#editModal").modal("hide");
                            $(".container-fluid").prepend("<div class='alert alert-success alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                        },
                    })
                }
            })

            $.ajax({
                url: "/stepsinfo",
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

            $(document).on("click", ".deletebanner", function() {
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
                                url: "/stepsinfo/delete",
                                method: "POST",
                                data: {
                                    id: id
                                },
                                success: function() {
                                    swal("Banner deleted successfully!", {
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
                            swal("Your Banner is safe!");
                        }
                    })
            })

        }
    )
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>