<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
        <?php
            if (isset($_GET['msg']) || isset($msg['error'])) {
            ?>
                <div class="alert alert-primary alert-dismissible fade show">
                    <?php if(isset($_GET['msg'])){echo $_GET['msg']; }else if(isset($msg['error'])){echo $msg['error'];} ?>
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
                                            <th scope="col">Timeslot Min Time</th>
                                            <th scope="col">Timeslot Max Time</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= date("h:i a", strtotime($mintime)) ?></h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><?= date("h:i a", strtotime($maxtime)) ?></h5>
                                                </td>
                                                <td>
                                                    <a href="#" class="text-danger deleteTime" id="<?= $id ?>"><i class="bx bx-trash-alt"></i></a>
                                                    <a href="#" id="<?= $id ?>" class="editTime"><i class="bx bx-edit"></i></a>
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

<div class="modal fade hide" role="dialog" id="editTimeslot" aria-labelledby="editTimeslotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/timeslots/edit" method="Post" id="editTimeslotForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h4 class="modal-title">Edit Timeslot</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productname">Min Time Slot</label>
                        <input type="time" class="form-control" required name="minTime" id="minTime" />
                        <div class="invalid-feedback">
                            Please enter a valid Min time
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Time Slot</label>
                        <input type="time" class="form-control" required name="maxTime" id="maxTime" />
                        <div class="invalid-feedback">
                            Please enter a valid Max time
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="timeslotid">
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
            $(document).on("click", ".editTime", function() {
                var timeslotid = $(this).attr('id');
                $("#timeslotid").val(timeslotid);

                $.ajax({
                    url: "/timeslot?id=" + timeslotid,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#editTimeslot").modal("show");
                        $("#minTime").val(data[0].mintime);
                        $("#maxTime").val(data[0].maxtime);
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

        }
    )
</script>