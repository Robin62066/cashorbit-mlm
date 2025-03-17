<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = "members";

$id = $_GET['id'] ?? "";
$items = $db->select('ai_users', ['id' => $id], 1)->row();
if ($items->ac_status == 1) {
    session()->set_flashdata('danger', "Id {$id} Already Activated");
} else {
    if (isset($_POST['submit'])) {
        $filds = [];
        $filds['user_id'] = $id;
        $filds['total_amt'] = 1550;
        $filds['status'] = 0;
        $db->insert('ai_loans', $filds);

        $userObj = new User();
        $userObj->activateAccountByLoan($id);

        session()->set_flashdata('success', "Id {$id} successfully activated");
    }
}




include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Activate Account</h5>
    </div>
    <div class="bg-white p-3">
        <form action="" method="post">
            <div class="row">
                <div class="col-sm-8">
                    <div class="box">
                        <div class="box-p">
                            <div class="form-group row">
                                <label class="col-sm-2">User Id</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->id; ?>" class="form-control input-sm" />
                                </div>
                                <label class="col-sm-2">Name</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->last_name . " " . $items->last_name; ?>" class="form-control input-sm" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Mobile no</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->mobile; ?>" class="form-control input-sm">
                                </div>
                                <label class="col-sm-2">Password</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->passwd; ?>" class="form-control input-sm">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Email Id</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->email_id; ?>" class="form-control input-sm">
                                </div>
                                <label class="col-sm-2">Sponser Id</label>
                                <div class="col-sm-4">
                                    <input type="text" disabled value="<?= $items->sponsor_id; ?>" class="form-control input-sm">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">&nbsp;</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="submit" value="Submit">
                                    <button class="btn btn-primary btn-submit">Activate</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<?php
include "../common/footer.php";
?>
<script>
    new Vue({
        el: '#origin',
        data: {
            isDisabled: false
        },
        methods: {
            disableButton: function() {
                this.isDisabled = true;
            }
        }
    });
</script>