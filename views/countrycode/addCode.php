<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add Country Code</h4>
                            <form class="needs-validation" action="" method="post" novalidate>
                                <div class="mb-3">
                                    <label class="form-label">Country Code</label>
                                    <input type="text" class="form-control" required name="cc" />
                                    <div class="invalid-feedback">
                                        Please enter a valid code
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Code Status</label>
                                    <select class="form-select" required name="codeStatus">
                                        <option selected="" disabled="" value="">Select Code Status</option>
                                        <option value="publish">Publish</option>
                                        <option value="unpublish">Unpublish</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid code status.
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