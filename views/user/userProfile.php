<?php

use app\models\DB;

$db = new DB();
$conn = $db->conn();
$username = $_SESSION['user'];
$result = $conn->query("select a.*, t.id as tid, t.active from admin a cross join testing t where username='$username'");
$array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($array, $row);
    }
}
foreach ($array as $item) {
    foreach ($item as $key => $value) {
        $$key = $value;
    }
}
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        <?php
            if (isset($_GET['msg']) || isset($msg['error'])) {
            ?>
                <div class="alert alert-primary alert-dismissible fade show">
                    <?php if(isset($_GET['msg'])){echo $_GET['msg']; }else if(isset($msg['error'])){echo $msg['error'];} ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            ?>
            <div class="card mt-4 p-4">
                <div class="cardbody">
                    <!-- <div class="row overflow-hidden" style="width: '100%'; padding-top: '75%'"> -->
                        <!-- style="height: 200px" -->
                        <!-- <div class="col-12"> -->
                            <!-- <img src="/assets/images/adminpage.jpg" alt="" class="img-fluid">
                        </div> -->
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
                            <form action="/profile/test" method="POST">
                                <div class="row mb-3">
                                    <label class="form-label col-sm-3 col-form-label">App status: </label>
                                    <div class="col-sm-9">
                                        <p class="mt-2 d-inline-block px-1"><?= $active == 1 ? "Active" : "Inactive"; ?></p>
                                        <!-- <input type="text" class="form-control" required name="username" value="<?= $active ?>" /> -->
                                        <?php
                                            if($active == 1){
                                                ?>
                                                <div class="row">
                                                    <input type="hidden" name="id" value="<?= $tid ?>">
                                                    <input type="hidden" name="active" id="active" value="<?= $active == 1 ? 0 : 1 ?>">
                                                    <div class="col-sm-4 mb-3">
                                                        <input type="text" name="title" class="form-control" placeholder="Title" required>
                                                    </div>
                                                    <div class="col-sm-4 mb-3">
                                                        <input type="text" name="message" class="form-control" placeholder="Message" required>
                                                    </div>
                                                    <div class="col-sm">
                                                        <button type="submit" class="btn btn-<?= $active == 1 ? "danger" : "primary" ?>"><?= $active == 1 ? "Make Inactive" : "Make Active" ?></button>
                                                    </div>
                                                </div>
                                                <?php
                                            }else if($active == 0){
                                                ?>
                                                <input type="hidden" name="id" value="<?= $tid ?>">
                                                <input type="hidden" name="active" id="active" value="<?= $active == 1 ? 0 : 1 ?>">
                                                <button type="submit" class="btn btn-<?= $active == 1 ? "danger" : "primary" ?>"><?= $active == 1 ? "Make Inactive" : "Make Active" ?></button>

                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid Username.
                                    </div>
                                </div>
                            </form>
                            <form class="needs-validation" action="" method="post" novalidate>
                                <div class="row mb-3">
                                    <label class="form-label col-sm-3 col-form-label">UserName: </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" required name="username" value="<?= $username ?>" />
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid Username.
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="form-label col-sm-3 col-form-label">E-mail: </label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" required name="email" value="<?= $email ?>" />
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid email.
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="form-label col-sm-3 col-form-label">Password: </label>
                                    <div class="col-sm-9">
                                        <div class="input-group auth-pass-inputgroup">
                                            <input name="pass" type="password" class="form-control" required value="<?= $password ?>" />
                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
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