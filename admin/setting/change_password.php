<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'settings';
if (isset($_POST['btn_update'])) {
    $pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $cnew_pass = $_POST['cnew_pass'];
    if ($new_pass == $cnew_pass) {
        $items = $db->select('ai_admin', [], false, "id DESC")->row();
        if ($items->passtext == $pass) {
            $sb = [];
            $sb['password'] = md5($new_pass);
            $sb['passtext'] = $new_pass;

            $db->update('ai_admin', $sb, ['passtext' => $pass]);
            session()->set_flashdata('success', 'Media updated successfully');
        } else {
            set_flashdata('danger', 'password is incorrect');
        }
    } else {
        set_flashdata('danger', 'new pass and confirm new pass is not match');
    }
}


include "../common/header.php";



?>

<div class="main-content">

    <div class="row">

        <div class="col-sm-12">

        </div>

    </div>

    <div class="page-header">

        <h5>Change Password</h5>

    </div>

    <div class="row" id="add-template">

        <div class="col-sm-7 ">

            <form action="" class="form-horizontal well" method="post">

                <div class="card card-info p-3">

                    <p><i>Fill the details to change password.</i></p>

                    <div class="form-group row">

                        <label class="col-sm-3 control-label">Old Password</label>

                        <div class="col-sm-8">

                            <input type="password" name="old_pass" class="form-control" name="old_pass" placeholder="Old Password" required />

                        </div>

                    </div>

                    <div class="form-group row">

                        <label class="col-sm-3 control-label">New Password</label>

                        <div class="col-sm-8">

                            <input type="password" name="new_pass" class="form-control" name="new_pass" placeholder="New Password" required />

                        </div>

                    </div>

                    <div class="form-group row">

                        <label class="col-sm-3 control-label">Retype Password</label>

                        <div class="col-sm-8">

                            <input type="password" name="cnew_pass" class="form-control" name="cnf_pass" placeholder="Confirm Password" required />

                        </div>

                    </div>



                    <div class="form-group row">

                        <div class="col-sm-8 offset-sm-3 d-flex gap-2">

                            <div><input type="hidden" name="btn_update" value="Update">
                                <button type="submit" class="btn btn-primary btn-submit">Update</button>
                            </div>

                            <div>
                                <a href="<?= admin_url('setting/change_password.php') ?>" class="btn btn-dark"><i class="fa fa-close"></i> Cancel</a>
                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <?php

    include "../common/footer.php";
