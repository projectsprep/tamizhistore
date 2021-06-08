<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add Area</h4>
                            <form class="needs-validation" action="" method="post" novalidate>
                                <div class="mb-3">
                                    <label for="productname">Area Name</label>
                                    <input type="text" class="form-control" required name="areaName" />
                                    <div class="invalid-feedback">
                                        Please enter a valid Area name
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Delivery Charge</label>
                                    <input type="number" class="form-control" required name="delCharge" />
                                    <div class="invalid-feedback">
                                        Please enter a valid Date.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" required name="status">
                                        <option selected="" value="1">Publish</option>
                                        <option value="0">Unpublish</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid coupon status.
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
</div>