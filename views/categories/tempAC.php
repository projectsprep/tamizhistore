<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <span id="errorMessage"></span>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Add Category</h4>

                            <form class="needs-validation" action="" method="post" novalidate enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Category name</label>
                                    <input type="text" class="form-control" id="categoryName" name="categoryName" required>
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
                                    <label class="form-label">Category Image</label>
                                    <input type="file" class="form-control" required id="categoryImage" name="categoryimage"/>
                                    <div class="invalid-feedback">
                                        Please select a valid category image
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
<script src="/assets/libs/jquery/jquery.min.js"></script>

<script>
    $(document).ready(
        function(){
            $(document).submit(function(e){
                var form_data = new FormData();
                form_data.append("categoryName", document.getElementById("categoryName").value);
                form_data.append("categoryimage", document.getElementById("categoryImage").files[0].name);
                $.ajax({
                    url: "/categorylist/add",
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function(){
                        console.log("uploading...");
                    },
                    success: function(){
                        $(".container-fluid").prepend("<div class='alert alert-primary alert-dismissible fade show'>Category added successfully <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>")
                    }
                })
                // e.preventDefault();
            })
        }
    )
</script>