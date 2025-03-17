<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'catalog';

if (isset($_GET['act']) && $_GET['act'] == 'del') {

    $id = $_GET['id'];

    $db->delete('ai_categories', ['id' => $id]);

    set_flashdata("success", "A record deleted");
}

$items = $db->select('ai_categories', [], false, "id DESC")->result();

include "../common/header.php";



?>

<div id="origin">

    <div class="page-header">

        <h5>Categories</h5>

        <a href="#" class="btn btn-primary btn-sm">Add Categories</a>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>Name</th>

                    <th>Status</th>

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

                        <td><?= $item->name; ?></td>

                        <?php

                        if ($item->status == 1) {

                        ?>

                            <td>

                                <i class="bg-success p-1 text-light">Active</i>

                            </td>

                        <?php } else {



                        ?>

                            <td>

                                <i class="bg-danger p-1 text-light">Active</i>

                            </td>

                        <?php

                        } ?>



                        <td>

                            <div class="d-flex gap-2">

                                <a href="<?= admin_url('catalog/edit-categories.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary"><i class="bi-pen"></i></a>

                                <a href="<?= admin_url('catalog/categories.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>

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
