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
                                    <label for="formrow-firstname-input" class="form-label">DeliveryBoy Name</label>
                                    <input type="text" class="form-control" id="formrow-firstname-input" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">DeliveryBoy Mobile</label>
                                    <input type="number" class="form-control" id="formrow-firstname-input" name="mobile" required>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">DeliveryBoy username</label>
                                    <input type="text" class="form-control" id="formrow-firstname-input" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">DeliveryBoy Address</label>
                                    <input type="text" class="form-control" id="formrow-firstname-input" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">DeliveryBoy Password</label>
                                    <input type="password" class="form-control" id="formrow-firstname-input" name="password" required>
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