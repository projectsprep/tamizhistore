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
                                            <?php 
                                                foreach($params as $param){
                                                    foreach($param as $key=>$value){
                                                        $$key=$value;
                                                    }
                                                }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="productname">Category Name</label>
                                                        <input id="productname" name="categoryName" type="text" class="form-control" value="<?= $catname?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="productname">Image</label>
                                                        <img src="<?=$catimg?>" alt="">
                                                        <div class="fallback">
                                                            <input name="categoryImage" type="file" />
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="<?=$id?>" name="id">
                                                </div>
                                            </div>
        
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                                                <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button>
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