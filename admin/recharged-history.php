<?php
include "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$sql = "SELECT * FROM ai_recharges WHERE status IN (1, 2, 3) ORDER BY id DESC";

$items = $db->query($sql)->result();
include "common/header.php";

?>

<div id="origin">

    <div class="page-header">

        <h5>Recharge Success History</h5>

    </div>
    <div class="bg-white p-3">

        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Info</th>
                    <th>Amount</th>
                    <th>Mobile Number</th>
                    <th>Operator</th>
                    <th>Recharge Date</th>
                    <th>Request Date</th>
                    <th>status</th>
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
                                <?= $user->email_id; ?>
                            </a>
                        </td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->mobile; ?></td>
                        <td><?= $item->operator; ?></td>
                        <td><?= $item->next_recharge; ?></td>
                        <td><?= $item->recharge_date; ?></td>
                        <td>
                            <?php
                            if ($item->status == 2) {
                            ?>
                                <i class="bg-warning p-1 text-light">Pending</i>
                            <?php } else if ($item->status == 3) {
                            ?>
                                <i class="bg-danger p-1 text-light">Rejected</i>
                            <?php
                            } else {   ?>
                                <i class="bg-success p-1 text-light">Paid</i>
                            <?php
                            } ?>
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

include "common/footer.php";
