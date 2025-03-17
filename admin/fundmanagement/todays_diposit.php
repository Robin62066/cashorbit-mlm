<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = 'fundmanagement';
$items = $db->select('ai_fund_request', ['status' => 0], false, "id DESC")->result();
include "../common/header.php";
?>

<div id="origin">
    <div class="page-header">
        <h5>Pending Deposit List</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>

                    <th>Sl</th>
                    <th>Username</th>
                    <th>Amount</th>
                    <th>Wallet</th>
                    <th>TXN Number</th>
                    <th>Screenshot</th>
                    <th>Created</th>
                    <th>Action</th>

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
                        <td>
                            <a href="#">
                                <?= $user->first_name; ?><br>
                                <?= $user->username; ?><br>
                                <?= $user->mobile; ?><br>
                            </a>
                        </td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->fund_type == 1 ? "Fund Wallet" : "Recharge Wallet"; ?></td>
                        <td><?= $item->txn_no; ?></td>
                        <td>
                            <?php
                            if ($item->screenshot != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->screenshot)) ?>" width="100" />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('fundmanagement/fund-desposit-action.php?action=confirm&id=' . $item->id); ?>" class="btn btn-xs btn-primary btn-confirm" data-msg="Are you sure to Confirm?">Approve</a>
                                <a href="<?= admin_url('fundmanagement/fund-desposit-action.php?action=decline&id=' . $item->id); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Delete?">Reject</a>
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
