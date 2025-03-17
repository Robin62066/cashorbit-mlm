<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = "members";
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Inactive Members</h5>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Info</th>
                    <th>Password</th>
                    <th>Sponsor Id</th>
                    <th>Position</th>
                    <th>Options </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                $items = $db->select('ai_users', ['ac_status' => 0], false, "created DESC")->result();
                foreach ($items as $item) {
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td>
                            <a href="#">
                                <?= $item->first_name; ?><br>
                                <?= $item->username; ?><br>
                                <?= $item->mobile; ?><br>
                                <?= $item->email_id; ?>
                            </a>
                        </td>
                        <td><?= $item->passwd; ?></td>
                        <td><?= $item->sponsor_id; ?></td>
                        <td>
                            <?php if ($item->position == 1) { ?>
                                <i>Left</i>
                            <?php } else if ($item->position == 2) { ?>
                                <i>Right</i>
                            <?php } else { ?>
                                <i></i>
                            <?php } ?>
                        </td>


                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('members/activate-account.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">Activate</a>

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
?>