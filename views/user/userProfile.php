<?php
    use app\models\DB;
    $db = new DB();
    $conn = $db->conn();
    $username = $_SESSION['user'];
    $result = $conn->query("select * from admin where username='$username'");
    $array = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            array_push($array, $row);
        }
    }
    foreach($array as $item){
        foreach($item as $key=>$value){
            $$key=$value;
        }
    }
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="card mt-4 p-4">
                <div class="cardbody">
                    <div class="row overflow-hidden" style="height: 200px">
                        <div class="col-12">
                            <img src="/assets/images/2153.jpg" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3 text-center">
                                <div class="avatar-lg profile-user-wid mx-auto mb-2" style="margin-top: -75px;">
                                    <img src="/assets/images/firsticon.png" alt="" class="img-thumbnail rounded-circle">
                                </div>
                                <h5 class="font-size-15 text-truncate">Tamizhistore</h5>
                                <p class="text-muted mb-0 text-truncate">Admin</p>
                            </div>
                            <form class="needs-validation" action="" method="post" novalidate>
                                        <div class="row mb-3">
                                            <label class="form-label col-sm-3 col-form-label">UserName: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" required name="username" value="<?=$username?>"/>
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter a valid Username.
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label col-sm-3 col-form-label">E-mail: </label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" required name="email" value="<?=$email?>"/>
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter a valid email.
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label col-sm-3 col-form-label">Is Admin</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" disabled required value="<?= $is_admin ? 'True' : 'False'?>"/>
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
        </div>
    </div>
</div>