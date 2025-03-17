<?php

include "../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$sql = "SELECT * FROM ai_boosting ORDER BY id ASC";
$items = $db->query($sql)->result();
include "common/header.php";
?>

<div id="origin">
    <div class="page-header">
        <h5>Boosting Report History</h5>
    </div>
    <div class="bg-white p-3">

        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Info</th>
                    <th>Sponsor ID</th>
                    <th>Created</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($items as $item) {
                    $user = $db->select('ai_users', ['id' => $item->user_id], 1, "id DESC")->row();
                    $sponsor = '-';
                    if ($item->sponsor_id > 0) {
                        $sb_id = $db->select('ai_boosting', ['id' => $item->sponsor_id], 1, "id DESC")->row()->user_id;
                        $user1 = $db->select('ai_users', ['id' => $sb_id], 1, "id DESC")->row();
                        $sponsor = $user1->first_name .  ' ' . $user1->last_name . '<br />' . $user1->username;
                    }

                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td>
                            <a href="#">
                                <?= $user->first_name .  ' ' . $user->last_name; ?><br />
                                <?= id2userid($item->user_id); ?>
                            </a>
                        </td>
                        <td><?= $sponsor; ?></td>
                        <td><?= $item->created; ?></td>
                        <td>
                            <?php
                            if ($item->status == 1) echo "Waiting";
                            if ($item->status == 0) echo "Upgraded";
                            ?>
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
