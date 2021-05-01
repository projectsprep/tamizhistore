<?php

use app\models\DB;

$db = new DB();
$conn = $db->conn();
$result = $conn->query("select id, catname from category");
foreach ($params as $param) {
    foreach ($param as $key => $value) {
        $$key = $value;
    }
}
?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Basic Information</h4>
                            <p class="card-title-desc">Fill all information below</p>

                            <form action="" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" required name="productName" value="<?= $pname ?>" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Seller Name / Shop Name</label>
                                    <div>
                                        <input type="text" id="pass2" class="form-control" required name="sellerName" value="<?= $sname ?>" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label">Select Category</label>
                                    <select class="form-select" required="">
                                        <option selected="" disabled="" value="">choose...</option>
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
                                    <label for="validationCustom03" class="form-label">Select Subcategory</label>
                                    <select class="form-select" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <option>...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Subcategory.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label">Out of Stock?</label>
                                    <select class="form-select" required="">
                                        <option selected="" value="<?= $stock == '1' ? 'yes' : 'no' ?>"><?= $stock == '1' ? 'Yes' : 'No' ?></option>
                                        <option value="<?= $stock == '1' ? 'no' : 'yes' ?>"><?= $stock == '1' ? 'No' : 'Yes' ?></option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label">Product publish or unpublish?</label>
                                    <select class="form-select" required="">
                                        <option selected="" value="<?= $status == '1' ? 'publish' : 'unpublish' ?>"><?= $status == '1' ? 'Publish' : 'Unpublish' ?></option>
                                        <option value="<?= $status == '1' ? 'unpublish' : 'publish' ?>"><?= $status == '1' ? 'Unpublish' : 'Publish' ?></option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label">Make product popular?</label>
                                    <select class="form-select" required="">
                                        <option selected="" value="<?= $status == '1' ? '1' : '0' ?>"><?= $status == '1' ? 'Yes' : 'No' ?></option>
                                        <option value="<?= $status == '1' ? '0' : '1' ?>"><?= $status == '1' ? 'No' : 'Yes' ?></option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Product Description</label>
                                    <div>
                                        <textarea required class="form-control" rows="3"><?= $psdesc ?></textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label">Product (GMS,KG,LTR,ML,PCS)</label>
                                    <!-- <select class="form-select" required="">
                                            <option selected="" value="KG"></option>
                                            <option value="KG">KG</option>
                                            <option value="GMS">GMS</option>
                                            <option value="LTR">LTR</option>
                                            <option value="ML">ML</option>
                                            <option value="PCS">PCS</option>
                                        </select> -->
                                    <div>
                                        <input type="text" id="pass2" class="form-control" required name="pgms" value="<?= $pgms ?>" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Product Price (<i class="bx bx-rupee"></i>)</label>
                                    <div>
                                        <input type="text" id="pass2" class="form-control" required name="sellerName" value="<?= $pprice ?>" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Product Discount (in digits)</label>
                                    <div>
                                        <input type="number" id="pass2" class="form-control" name="sellerName" min='0' max="100" value="<?= $discount ?>" />
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
                    </div>

                    <!-- end card-->
                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


</div>
<!-- end main content-->