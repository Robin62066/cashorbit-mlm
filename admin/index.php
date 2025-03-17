<?php
include "../config/autoload.php";
if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $where = "username = '$username' and password = '$password'";
    $result = $db->select('ai_admin', $where, 1);
    if ($result->count() == 1) {
        $us = $result->row();
        set_userdata('admin', $us);
        redirect('dashboard.php');
    } else {
        set_flashdata('error_msg', "Admin not found. Please check your username.");
        redirect('index.php');
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?= base_url('assets/admin/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/admin/css/style.css'); ?>" rel="stylesheet" />
</head>

<body> <!-- Add the body tag here -->
    <div class="container" style="margin-top:120px;">
        <div class="col-sm-6 offset-sm-3">
            <?php include "common/alert.php"; ?>
            <div class="card">
                <div class="card-header">
                    <b class="box-title">Secure Login</b>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group row">
                            <label class="col-lg-3" for="username">Username:</label>
                            <div class="col-lg-6">
                                <input type="text" name="username" value="" placeholder="Username" class="form-control input-sm" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3" for="password">Password:</label>
                            <div class="col-lg-6">
                                <input type="password" name="password" placeholder="Password" class="form-control input-sm" value="" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 offset-sm-3">
                                <button type="submit" value="Login" name="submit" class="btn btn-primary"><i class="fa fa-lock"></i> Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body> <!-- Close the body tag -->

</html> <!-- Close the HTML tag -->