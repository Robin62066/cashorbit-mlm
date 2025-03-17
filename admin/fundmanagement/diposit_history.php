<?php

include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'fundmanagement';

$sql = "SELECT * FROM ai_fund_request WHERE status != 0 ORDER BY id DESC";
$items = $db->query($sql)->result();
include "../common/header.php";

?>

<div id="origin">

    <div class="page-header">

        <h5>Payment Deposite List</h5>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>
                    <th>Username</th>
                    <th>Amount</th>
                    <th>TXN Number</th>
                    <th>Screenshot</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>

            </thead>

            <tbody>

                <?php

                $sl = 1;

                foreach ($items as $item) {

                    $user = $db->select('ai_users', ['id' => $item->user_id], false, "id DESC")->row();
                    if ($user == null) continue;
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
                        <td>
                            <?php
                            if ($item->status == 1) {
                            ?>
                                <span class="badge bg-success">Approved</span>
                            <?php } else if ($item->status == 2) {
                            ?>
                                <span class="badge bg-danger">Declined</span>
                            <?php
                            } ?>
                        </td>
                        <td><?= $item->created; ?></td>
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
