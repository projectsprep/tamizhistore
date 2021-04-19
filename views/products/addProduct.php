<div class="main-content">

        <div class="page-content">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Product</h4>
                                <form class="custom-validation" action="" method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" required name="productName"/>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Seller Name / Shop Name</label>
                                        <div>
                                            <input type="text" id="pass2" class="form-control" required name="sellerName"/>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Select Category</label>
                                        <select class="form-select" id="validationCustom03" required="" name="category">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option>...</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid Category.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Select Subcategory</label>
                                        <select class="form-select" id="validationCustom03" required="" name="subCategory">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <option>...</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid Subcategory.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Out of Stock?</label>
                                        <select class="form-select" id="validationCustom03" required="" name="outofstock">
                                            <option selected="" value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Product publish or unpublish?</label>
                                        <select class="form-select" id="validationCustom03" required="" name="publish">
                                            <option selected="" value="publish">Publish</option>
                                            <option value="unpublish">Unpublish</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Make product popular?</label>
                                        <select class="form-select" id="validationCustom03" required="" name="popular">
                                            <option selected="" value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Description</label>
                                        <div>
                                            <textarea required class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="validationCustom03" class="form-label">Product (GMS,KG,LTR,ML,PCS)</label>
                                        <select class="form-select" id="validationCustom03" required="" name="unit">
                                            <option selected="" value="KG">KG</option>
                                            <option value="GMS">GMS</option>
                                            <option value="LTR">LTR</option>
                                            <option value="ML">ML</option>
                                            <option value="PCS">PCS</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Price (<i class="bx bx-rupee"></i>)</label>
                                        <div>
                                            <input type="number" id="pass2" class="form-control" required name="price"/>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Discount (in digits)</label>
                                        <div>
                                            <input type="number" id="pass2" class="form-control" name="discount" min='0' max="100"/>
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
                    </div> <!-- end col -->

                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


    </div>
    <!-- end main content-->