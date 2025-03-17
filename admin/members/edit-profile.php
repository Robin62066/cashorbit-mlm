<?php

include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$id = $_GET['id'] ?? "";
if (isset($_POST['submit'])) {
    $form = $_POST['form'];
    $db->update('ai_users', $form, ['id' => $id], 1);
    session()->set_flashdata('success', 'Profile details updated');
    redirect(current_url());
}

$menu = 'catalog';
$items = $db->select('ai_users', ['id' => $id], 1)->row();
include "../common/header.php";
?>
<div id="origin" class="main-content">
    <div class="row">
        <div class="col-sm-12">
        </div>
    </div>
    <div class="page-header">
        <h5>Edit Profile : <?= $items->username; ?></h5>
    </div>

    <form action="" method="post">
        <div class="row">
            <div class="col-sm-8">
                <div class="box">
                    <div class="box-p">
                        <p><em>Edit the details carefully !!</em></p>
                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-2">First name</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[first_name]" value="<?= $items->first_name; ?>" class="form-control input-sm" />
                            </div>
                            <label class="col-sm-2">Last name</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[last_name]" value="<?= $items->last_name; ?>" class="form-control input-sm" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Mobile no</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[mobile]" value="<?= $items->mobile; ?>" class="form-control input-sm">
                            </div>
                            <label class="col-sm-2">Password</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[passwd]" value="<?= $items->passwd; ?>" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Email Id</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[email_id]" value="<?= $items->email_id; ?>" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">Aadhar no</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[adhar_no]" value="<?= $items->adhar_no; ?>" class="form-control input-sm">
                            </div>
                            <label class="col-sm-2">PAN no</label>
                            <div class="col-sm-4">
                                <input type="text" name="form[pan_no]" value="<?= $items->pan_no; ?>" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">State</label>
                            <div class="col-sm-4">
                                <select name="form[state]" class="form-control">
                                    <option value="1">Andaman and Nicobar Islands</option>
                                    <option value="2">Andhra Pradesh</option>
                                    <option value="3">Arunachal Pradesh</option>
                                    <option value="4">Assam</option>
                                    <option value="5">Bihar</option>
                                    <option value="6">Chandigarh</option>
                                    <option value="7">Chhattisgarh</option>
                                    <option value="8">Dadra and Nagar Haveli</option>
                                    <option value="9">Daman and Diu</option>
                                    <option value="10">Delhi</option>
                                    <option value="11">Goa</option>
                                    <option value="12">Gujarat</option>
                                    <option value="13">Haryana</option>
                                    <option value="14">Himachal Pradesh</option>
                                    <option value="15">Jammu and Kashmir</option>
                                    <option value="16">Jharkhand</option>
                                    <option value="17">Karnataka</option>
                                    <option value="18">Kenmore</option>
                                    <option value="19">Kerala</option>
                                    <option value="20">Lakshadweep</option>
                                    <option value="21">Madhya Pradesh</option>
                                    <option value="22">Maharashtra</option>
                                    <option value="23">Manipur</option>
                                    <option value="24">Meghalaya</option>
                                    <option value="25">Mizoram</option>
                                    <option value="26">Nagaland</option>
                                    <option value="29">Odisha</option>
                                    <option value="31">Pondicherry</option>
                                    <option value="32">Punjab</option>
                                    <option value="33">Rajasthan</option>
                                    <option value="34">Sikkim</option>
                                    <option value="35">Tamil Nadu</option>
                                    <option value="36">Telangana</option>
                                    <option value="37">Tripura</option>
                                    <option value="38">Uttar Pradesh</option>
                                    <option value="39">Uttarakhand</option>
                                    <option value="41">West Bengal</option>
                                </select>

                            </div>
                            <label class="col-sm-2">Login Status</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="form[status]">
                                    <option <?= $items->status == 0 ? 'selected' : ''; ?> value="0">Block</option>
                                    <option value="1" <?= $items->status == 1 ? 'selected' : ''; ?>>Unblock</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">KYC Status</label>
                            <div class="col-sm-4">
                                <select name="form[kyc_status]" class="form-control">
                                    <option value="1" <?= $items->kyc_status == 0 ? 'selected' : ''; ?>>Approved</option>
                                    <option value="0" <?= $items->kyc_status == 1 ? 'selected' : ''; ?>>Pending</option>
                                </select>
                            </div>
                            <label class="col-sm-2">Bank Edit</label>
                            <div class="col-sm-4">
                                <select name="form[bank_edit]" class="form-control">
                                    <option value="1" <?= $items->bank_edit == 1 ? 'selected' : ''; ?>>Allow</option>
                                    <option value="0" <?= $items->bank_edit == 0 ? 'selected' : ''; ?>>Blocked</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2">Block Msg</label>
                            <div class="col-sm-10">
                                <input type="text" name="form[block_msg]" value="<?= $items->block_msg; ?>" class="form-control">
                            </div>
                        </div>

                        <div class="page-header">
                            <h5>Bank Details</h5>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>PhonePe</label>
                                <input class="form-control" type="text" value="<?= $items->phonepe; ?>" name="form[phonepe]">

                                <label>Google Pay</label>
                                <input class="form-control" type="text" value="<?= $items->google; ?>" name="form[google]">

                                <label>PayTM</label>
                                <input class="form-control" type="text" value="<?= $items->paytm; ?>" name="form[paytm]">
                            </div>
                            <div class="col-sm-6">
                                <label>Bank Name</label>
                                <input class="form-control" type="text" value="<?= $items->bank_name; ?>" name="form[bank_name]">

                                <label>Account Number</label>
                                <input class="form-control" type="text" value="<?= $items->bank_ac_number; ?>" name="form[bank_ac_number]">

                                <label>IFSC Code</label>
                                <input class="form-control" type="text" value="<?= $items->bank_ifsc; ?>" name="form[bank_ifsc]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="submit" value="Submit">
                                <button class="btn btn-primary btn-submit">Save</button>
                                <a href="credit_debit.php" class="btn btn-dark">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
<?php
include "../common/footer.php";
?>
<script>
    new Vue({
        el: "origin",
        data: {
            "edit_bank": ""
        },
        methods: {
            myfun: function(name) {
                this.edit_bank = name;
            }
        }

    })
</script>