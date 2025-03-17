<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');
$menu = 'catalog';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_products', ['id' => $id]);
    set_flashdata("success", "Product is deleted");
}
$items = $db->select('ai_products', [], false, "id DESC")->result();
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>Manage Products</h5>
        <a href="<?= admin_url('catalog/add-new.php') ?>" class="btn btn-primary btn-sm">Add Categories</a>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>MRP</th>
                    <th>
                        Offer
                    </th>
                    <th>BV</th>
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
                        <td><?= $item->ptitle; ?></td>
                        <td>
                            <?php
                            if ($item->image != '') {
                            ?>
                                <img src="<?= base_url(upload_dir($item->image)) ?>" width="100" />
                            <?php
                            }
                            ?>
                        </td>
                        <?php
                        if ($item->status == 1) {
                        ?>
                            <td>
                                <i class="bg-success p-1 text-light">Active</i>
                            </td>
                        <?php } else { ?>
                            <td>
                                <i class="bg-danger p-1 text-light">Active</i>
                            </td>
                        <?php } ?>


                        <td><?= $item->dp; ?></td>
                        <td><?= $item->offer; ?></td>
                        <td><?= $item->bvp; ?></td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('catalog/categories.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary"><i class="bi-pen"></i></a>
                                <a href="<?= admin_url('catalog/products.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash"></i></a>
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
