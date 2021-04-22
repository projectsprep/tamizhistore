<div class="main-content">

        <div class="page-content">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Notification</h4>
                                <form class="needs-validation" action="" method="post" novalidate>
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" required name="title"/>
                                        <div class="invalid-feedback">
                                            Please enter a valid title
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="productname">Message</label>
                                        <input type="text" class="form-control" required name="message" />
                                        <div class="invalid-feedback">
                                            Please enter a valid message
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image">Image</label>
                                        <div class="fallback">
                                            <input type="file" multiple name="image" />
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