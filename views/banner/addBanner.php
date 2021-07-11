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
                            <h4 class="card-title mb-4">Add Banner</h4>

                            <form class="needs-validation" action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Banner Image</label>
                                    <input type="file" class="form-control" required name="image" />
                                    <div class="invalid-feedback">
                                        Please select a valid Banner image
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Category</label>
                                    <select class="form-select" required name="cid" id="category">
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
                                    <label class="form-label">Select Subcategory</label>
                                    <select class="form-select" required name="sid" id="subcategory">
                                        <option disabled="" selected>Choose...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid Category.
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $("#category").on("change", function(){
            var id = $(this).val()

            $.ajax({
                url: "/getsubcategorynames?id="+id,
                method: "GET",
                dataType: "json",
                success: function(data){
                    $("#subcategory").html(data)
                }
            })
        })
    })
</script>