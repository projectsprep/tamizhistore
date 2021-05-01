<?php

echo "<pre>";
var_dump($params);
echo "</pre>";
?>

<div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                    <?php
                        if(isset($_POST['cc']) && isset($_POST['codeStatus'])){
                            if($msg == true){
                        ?>
                                <div class="alert alert-success">
                                    Added new country code
                                </div>
                                <?php
                            }else if($msg == false){
                                ?>
                                <div class="alert alert-danger">
                                    Unable to create new country code
                                </div>
                                <?php
                            }
                        }

                    ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;">Sl.no</th>
                                                        <th scope="col">Country Code</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $i = 1;
                                                        foreach($params as $param){
                                                            foreach($param as $key=>$value){
                                                                $$key = $value;
                                                            }
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                        <span class="">
                                                                            <?=$i++;?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <h5 class="font-size-14 mb-1"><?= $ccode; ?></h5>
                                                                    </td>
                                                                    <td>
                                                                        <h5 class="font-size-14 mb-1"><?= $status ? "Active" : "Inactive"; ?></h5>
                                                                    </td>
                                                                    <td>
                                                                        <a href="/arealist/delete?id=<?= $id?>" class="text-danger"><i class="bx bx-trash-alt"></i></a>
                                                                        <a href="/arealist/edit?id=<?=$id?>"><i class="bx bx-edit"></i></a>
                                                                    </td>
                                                                    
                                                                </tr>
                                                                <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="pagination pagination-rounded justify-content-center mt-4">
                                                    <li class="page-item disabled">
                                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a href="#" class="page-link">1</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">2</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">3</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">4</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">5</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
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