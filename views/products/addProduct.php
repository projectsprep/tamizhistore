<?php
    use app\models\DB;
    $db = new DB();
    $conn = $db->conn();
    $result = $conn->query("select id, catname from category");
?>
<div class="main-content">

        <div class="page-content">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Product</h4>
                                <form class="needs-validation" action="" method="post" novalidate enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" required name="productName"/>
                                        <div class="invalid-feedback">
                                            Please enter a valid Product Name.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" class="form-control" required name="productimage"/>
                                        <div class="invalid-feedback">
                                            Please select a valid product image
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Seller Name / Shop Name</label>
                                        <input type="text" id="pass2" class="form-control" required name="sellerName"/>
                                        <div class="invalid-feedback">
                                            Please enter a valid Seller or Shop Name.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Select Category</label>
                                        <select class="form-select" required name="category" id="category">
                                            <option disabled="" selected>Choose...</option>
                                            <?php
                                                if($result->num_rows > 0){
                                                    while($row = $result->fetch_assoc()){    
                                                    ?>
                                                    <option value="<?= $row['id']?>"><?= $row['catname']?></option>
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
                                        <select class="form-select" required name="subCategory" id="subCategory">
                                            <option disabled="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid Subcategory.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Out of Stock?</label>
                                        <select class="form-select" required name="outofstock">
                                            <option selected="" value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid option.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product publish or unpublish?</label>
                                        <select class="form-select" required="" name="publish">
                                            <option selected="" value="publish">Publish</option>
                                            <option value="unpublish">Unpublish</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid option.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Make product popular?</label>
                                        <select class="form-select" required="" name="popular">
                                            <option selected="" value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid option.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Description</label>
                                        <div>
                                            <textarea required class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product (GMS,KG,LTR,ML,PCS..)</label>
                                        <input type="text" name="range" class="form-control">
                                        <div class="invalid-feedback">
                                            Please select a valid option.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Price (<i class="bx bx-rupee"></i>)</label>
                                        <div>
                                            <input type="number" class="form-control" required name="price"/>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid Product Price.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Discount (in digits)</label>
                                        <div>
                                            <input type="number" class="form-control" name="discount" min='0' max="100"/>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid Product Discount.
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
                        
                    </div>

                </div>

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script>
        $(document).ready(function(){
            $("#category").on("change",function(){
                var id = $(this).val();
                // console.log(id);
                $.ajax({
                    url: "/api/getsubcategorynames",
                    method: "POST",
                    data: {id: id},
                    dataType: "json",
                    success: function(data){
                        $("#subCategory").html(data);
                    },
                })
            })
        })
    </script>