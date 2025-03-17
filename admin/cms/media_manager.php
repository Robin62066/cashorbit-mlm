<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');



$menu = 'cms';

if (isset($_GET['act']) && $_GET['act'] == 'del') {

    $id = $_GET['id'];

    $db->delete('ai_media', ['id' => $id]);

    set_flashdata("success", "Recorde is deleted");
}

$items = $db->select('ai_media', [], false, "id DESC")->result();

include "../common/header.php";



?>

<div id="origin">

    <div class="page-header">

        <h5>Media Manager</h5>

        <a href="<?= admin_url('properties/add-new.php') ?>" class="btn btn-primary btn-sm">Upload Now</a>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>Thumbnail</th>

                    <th>Name</th>

                    <th>URL</th>

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

                        <td>

                            <?php

                            if ($item->file_name != '') {

                            ?>

                                <img src="<?= base_url(upload_dir($item->file_name)) ?>" width="100" />

                            <?php

                            }

                            ?>

                        </td>



                        <td><?= $item->img_title; ?></td>

                        <td>dgdfsg</td>

                        <td>

                            <div class="d-flex gap-2">



                                <a href="<?= admin_url('cms/media_manager.php?id=' . $item->id); ?>&act=del" target="_blank" class="btn btn-xs btn-danger text-light "><i class="bi-trash"></i></a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>



        </table>

    </div>

</div>

<?php

include "../common/footer.php";
