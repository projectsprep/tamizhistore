<?php
use app\models\ProductsModel;
use app\models\DB;
$db = new DB();
$db = $db->conn();
$result = $db->query("select p.id, p.pname, p.pimg, p.sname, category.catname, subcategory.name, p.pgms, p.pprice, p.stock, p.status from product p inner join category on p.cid = category.id inner join subcategory on p.sid=subcategory.id order by id desc ");
$pDB = new ProductsModel();

$resultPerPage = 10;
$numberOfResults = $result->num_rows;
$numberOfPages = ceil($numberOfResults / $resultPerPage);

if(!(isset($_GET['page']))){
    $page = 1;
}else{
    $page = $_GET['page'];
}

$pageFirstResult = ($page-1) * $resultPerPage;
$json = $pDB->read("product", $pageFirstResult, $resultPerPage);
$params = json_decode($json);
$j=$_GET['page'] ?? 1;
?>
<div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <p><?= "Page $page of $numberOfPages"?></p>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;">Sl.no</th>
                                                        <th scope="col">Product Name</th>
                                                        <th scope="col">Product Image</th>
                                                        <th scope="col">Seller name</th>
                                                        <th scope="col">Category Name</th>
                                                        <th scope="col">Subcategory Name</th>
                                                        <th scope="col">Product Price</th>
                                                        <th scope="col">Product Range</th>
                                                        <th scope="col">In Stock</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productlists">
                                                <td colspan="11" align="center">
                                                    <div class="spinner-border" role="status" id="loader">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="pagination pagination-rounded justify-content-center mt-4" style="overflow-x: auto; width: auto">
                                                        <li class="page-item <?= $page == 1? 'disabled' : ''?>">
                                                            <a href="/productlist?page=<?= $_GET['page'] -1?>" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                        </li>
                                                    <?php
                                                        for($page = 1, $count=0; $page<=$numberOfPages && $j<=$numberOfPages; $page++, $count++, $j++){
                                                    ?>
                                                        <li class="page-item">
                                                            <a href="/productlist?page=<?=$j?>" class="page-link"><?=$j?></a>
                                                        </li>
                                                    <?php
                                                    if($count==5){
                                                        $count=0;
                                                        break;
                                                    }
                                                        }
                                                    ?>
                                                    
                                                    <li class="page-item <?= $_GET['page'] == 141 ? 'disabled' : '' ?>">
                                                        <a href="/productlist?page=<?=$j+1?>" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                                    </li>
                                                    <li class="page-item <?= $_GET['page'] == $page ? 'active' : ''?>">
                                                        <a href="/productlist?page=<?=$numberOfPages?>" class="page-link"><?=$numberOfPages?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                          </div>

                          <script src="/assets/libs/jquery/jquery.min.js"></script>
<script>
$(document).ready(function(){
    loadCategoryList()
    function loadCategoryList(){
        $.ajax({
            url: "api/getproduct",
            method:"GET",
            data:{view:""},
            dataType: "json",
            beforeSend: function(){
                $("#loader").show();
            },
            success: function(data){
                setTimeout(function(){
                    $("#loader").hide();
                    $("#productlists").html(data);
                }, 2000);
            },
            error: function(xhr, ajaxOptions, thrownError){
                    alert(thrownError);
                },
        })
    }
});
</script>