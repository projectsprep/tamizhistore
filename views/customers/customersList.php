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
                                            <th scope="col">Name</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Register Date</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $username; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $created_at; ?></h5>
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
            $.ajax({
                url: "/customers",
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
        }
    )
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>