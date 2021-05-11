<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add Coupon</h4>
                            <form class="needs-validation" action="" method="post" novalidate enctype="multipart/form-data">
                                <!-- <div class="mb-3">
                                        <label for="couponImage">Image</label>
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
                                    <label class="form-label">Coupon Image</label>
                                    <input type="file" class="form-control" required name="couponimage" />
                                    <div class="invalid-feedback">
                                        Please select a valid coupon image
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Expiry Date</label>
                                    <input type="date" class="form-control" required name="expiryDate" />
                                    <div class="invalid-feedback">
                                        Please enter a valid Date.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Code</label>
                                    <input type="text" class="form-control" required name="couponCode" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Title</label>
                                    <input type="text" class="form-control" required name="couponTitle" />
                                    <div class="invalid-feedback">
                                        Please enter a valid title.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Status</label>
                                    <select class="form-select" required name="couponStatus">
                                        <option selected="" disabled="" value="">Select Coupon Status</option>
                                        <option value="publish">Publish</option>
                                        <option value="unpublish">Unpublish</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid coupon status.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Min Order Amount</label>
                                    <input type="Number" class="form-control" required name="minAmt" />
                                    <div class="invalid-feedback">
                                        Please enter a valid Date.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Value</label>
                                    <input type="Number" class="form-control" required name="discount" />
                                    <div class="invalid-feedback">
                                        Please enter a valid Date.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Coupon Description</label>
                                    <div>
                                        <textarea required class="form-control" rows="3" name="description"></textarea>
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