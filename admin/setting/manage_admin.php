<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = 'settings';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_admin', ['id' => $id]);
    set_flashdata("success", "Product is deleted");
}
$items = $db->select('ai_admin', [], false, "id DESC")->result();
include "../common/header.php";
?>

<div id="origin">

    <div class="page-header">

        <h5>Manage Admin</h5>

        <a href="<?= admin_url('catalog/add-new.php') ?>" class="btn btn-primary btn-sm">Add Categories</a>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>Full Name</th>

                    <th>Email Id</th>

                    <th>Username</th>

                    <th>Password</th>

                    <th>Role</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $sl = 1;

                foreach ($items as $item) {

                ?>

                    <tr>

                        <td><?= $sl++; ?></td>

                        <td><?= $item->first_name . " " . $item->last_name; ?></td>



                        <td><?= $item->email_id; ?></td>

                        <td><?= $item->username; ?></td>

                        <td><?= $item->password; ?></td>

                        <td><?= $item->role; ?></td>



                        <td>

                            <div class="d-flex gap-2">

                                <a href="<?= admin_url('setting/edit_profile.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary"><i class="bi-pen"></i></a>

                                <a href="<?= admin_url('setting/manage_admin.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>

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
