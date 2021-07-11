<?php

use app\models\DB;

$db = new DB();
$conn = $db->conn();
$result = $conn->query("select id, catname from category");
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
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Add Category</h4>

                            <form class="needs-validation" action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">SubCategory name</label>
                                    <input type="text" class="form-control" id="formrow-firstname-input" name="subcategoryname" required>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="productname">Image</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div action="#" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <div class="mb-3">
                                                        <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                                    </div>
                                                    <h4>Drop files here or click to upload.</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Select Category</label>
                                    <select class="form-select" required name="category" id="category">
                                        <option disabled="" selected>Choose...</option>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['catname'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Category.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="charge" class="form-label">Shop DoorNo.</label>
                                    <input type="text" class="form-control" name="doorno" required>
                                </div>
                                <div class="mb-3">
                                    <label for="charge" class="form-label">Shop AddressLine 1</label>
                                    <input type="text" class="form-control" name="addr1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="charge" class="form-label">Shop AddressLine 2</label>
                                    <input type="text" class="form-control" name="addr2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="charge" class="form-label">Shop Pincode</label>
                                    <input type="number" class="form-control" name="pincode" required>
                                </div>
                                <div class="mb-3">
                                    <label for="charge" class="form-label">Delivery Charge</label>
                                    <input type="number" class="form-control" name="charge" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SubCategory Image</label>
                                    <input type="file" class="form-control" required name="subcategoryimage" />
                                    <div class="invalid-feedback">
                                        Please select a valid category image
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Shop status</label>
                                    <select class="form-select" required name="shopstatus" id="shopstatus">
                                        <option value="1" selected="">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Status.
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                    <button type="reset" class="btn btn-secondary waves-effect">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/libs/dropzone/min/dropzone.min.js"></script>