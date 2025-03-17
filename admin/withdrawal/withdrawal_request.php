<?php

include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = 'withdrawal';

$status = $_GET['status'] ??  null;
$id = $_GET['id'] ??  null;
if ($status != null && $id != null) {
    $db->update('ai_withdraw', ['status' => $status], ['id' => $id]);
    redirect(admin_url('withdrawal/withdrawal_request.php'), 'Request updated successfully');
}

$items = $db->select('ai_withdraw', ['status' => 0], false, "id DESC")->result();
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Withdrawal Request</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>

                    <th>Sl</th>
                    <th>Created</th>
                    <th>Amount</th>
                    <th>Net Pay</th>
                    <th>UserInfo</th>
                    <th>Bank A/c Details</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    $user = $db->select('ai_users', ['id' => $item->user_id], false, "id DESC")->row();
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td><?= $item->created; ?></td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->paid_total; ?></td>
                        <td><a href="#">
                                <?= $user->username; ?><br>
                                <?= $user->first_name; ?><br>
                                Mobile:<?= $user->mobile; ?><br>
                            </a>
                        </td>
                        <td>
                            <?= $user->bank_name; ?><br>
                            <?= $user->bank_ac_number; ?><br>
                            <?= $user->bank_ifsc; ?>
                        </td>
                        <?php
                        if ($item->status == 1) {
                        ?>
                            <td>
                                <i class="bg-success p-1 text-light">Active</i>
                            </td>
                        <?php } else if ($item->status == 0) {
                        ?>
                            <td>
                                <i class="bg-warning p-1 text-light">Pending</i>
                            </td>
                        <?php } ?>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('withdrawal/withdrawal_request.php?id=' . $item->id . '&status=1'); ?>" class="btn btn-xs btn-primary btn-confirm" data-msg="Are you sure to Approve?">Approve</i></a>
                                <a href="<?= admin_url('withdrawal/withdrawal_request.php?id=' . $item->id . '&status=2'); ?>" target="_blank" class="btn btn-xs btn-dark text-light btn-confirm" data-msg="Are you sure to Reject?">Reject</i></a>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php

include "../common/footer.php";
