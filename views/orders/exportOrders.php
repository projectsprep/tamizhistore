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
                                            <th scope="col">Order ID</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Product Price</th>
                                            <th scope="col">Order Date</th>
                                            <th scope="col">Delivery Date</th>
                                            <th scope="col">Delivery Status</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Rider Status</th>
                                            <th scope="col">Rider Name</th>
                                            <th scope="col">Tax</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Coupon</th>
                                            <th scope="col">Coupon Amount</th>
                                            <th scope="col">Total Amount</th>
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
                                                    <h5 class="font-size-14 mb-1"><?= $oid; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $uid; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $pname; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $pprice; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $order_date; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $ddate; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $status; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $qty; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $r_status; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $rider; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $tax; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $address; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $coupon_id; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $cou_amt; ?></h5>
                                                </td>
                                                <td align="center">
                                                    <h5 class="font-size-14 mb-1"><?= $wal_amt; ?></h5>
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
                    <h4 class="modal-title">Edit Category</h4>
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

<style>
    @keyframes ldio-rpinwye8j0b {
  0% { transform: rotate(0deg) }
  50% { transform: rotate(180deg) }
  100% { transform: rotate(360deg) }
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
  transform-origin: 0 0; /* see note above */
}
.ldio-rpinwye8j0b div { box-sizing: content-box; }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript">
    $("#datatable").DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
</script>
<script>
    $(document).ready(
        function() {
            $.ajax({
                url: "/orders/export",
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
        }
    )
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
