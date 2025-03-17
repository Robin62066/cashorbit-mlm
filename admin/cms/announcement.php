<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'cms';

if (isset($_GET['act']) && $_GET['act'] == 'del') {

    $id = $_GET['id'];

    $db->delete('ai_posts', ['id' => $id]);

    set_flashdata("success", "Property list deleted");

}

$items = $db->select('ai_posts', [], false, "id DESC")->result();

include "../common/header.php";



?>

<div id="origin">

    <div class="page-header">

        <h5>Manage Announcement</h5>

        <a href="<?= admin_url('properties/add-new.php') ?>" class="btn btn-primary btn-sm">Add Announcement</a>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>Title</th>

                    <th>created</th>

                    <th>Post</th>

                    <th>Options</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $sl = 1;

                foreach ($items as $item) {



                ?>

                    <tr>

                        <td><?= $sl++; ?></td>

                        <td><?= $item->post_title; ?></td>

                        <td><?= $item->created; ?></td>

                        <td>

                            <?php

                            if ($item->image != '') {

                            ?>

                                <img src="<?= base_url(upload_dir($item->image)) ?>" width="100" />

                            <?php

                            }

                            ?>

                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                <a href="<?= admin_url('cms/announcement.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>

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

