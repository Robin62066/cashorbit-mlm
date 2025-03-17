<?php

include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'properties';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_users', ['id' => $id]);
    set_flashdata("success", "User list deleted");
}
$sql = "SELECT * FROM ai_users WHERE DATE(created) = CURDATE() ORDER BY id DESC";
$items = $db->query($sql)->result();
$menu = "members";
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>All Members::<span class="text-primary"> <?= count($items)  ?> Records Found </span></h5>

    </div>
    <div class="bg-white mb-3 p-3">
        <form action="" method="get">
            <div class="row mb-3 align-items-end">
                <div class="col-sm-3">
                    <label>Search</label>
                    <input name="q" value="" class="form-control" placeholder="Name, Userid or Email id, Mobile" />
                </div>
                <div class="col-sm-2">
                    <label>From</label>
                    <input class="form-control" value="" name="from" type="date" />
                </div>
                <div class="col-sm-2">
                    <label>To</label>
                    <input class="form-control" value="" name="to" type="date" />
                </div>
                <div class="col-sm-3">
                    <label>Search By</label>
                    <select name="ac_status" class="form-select form-select-sm">
                        <option value="0">Select</option>
                        <option value="1">Join Date</option>
                        <option value="2">Activation Date</option>
                    </select>
                </div>
                <div class="col-sm-2 d-grid">
                    <input type="submit" name="search" class="btn btn-primary" value="Search" />
                </div>
            </div>
        </form>
    </div>
    <div class="bg-white p-3">
        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Info</th>
                    <th>Password</th>
                    <th>Sponsor Id</th>
                    <th>Upline Id</th>
                    <th>A/c Active</th>
                    <th>Options </th>
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
                            <a href="#">
                                <?= $item->first_name; ?><br>
                                <?= $item->username; ?><br>
                                <?= $item->mobile; ?><br>
                                <?= $item->email_id; ?>
                            </a>
                        </td>
                        <td><?= $item->passwd; ?></td>
                        <td><?= $item->sponsor_id; ?></td>

                        <td><?= $item->username; ?></td>


                        <?php
                        if ($item->ac_status == 1) {
                        ?>
                            <td>
                                <i class="bg-success p-1 text-light">Active</i>
                            </td>
                        <?php } else {

                        ?>
                            <td>
                                <i class="bg-danger p-1 text-light">In-Active</i>
                            </td>
                        <?php
                        } ?>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('members/edit-profile.php?id=' . $item->id); ?>" class="btn btn-xs btn-primary">Edit</a>
                                <a href="https://accounts.cashorbit.net" target="_blank" class="btn btn-xs btn-dark text-light ">Login</i></a>
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
