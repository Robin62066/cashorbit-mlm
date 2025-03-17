<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');



$menu = "members";
include "../common/header.php";

?>
<div id="origin">
    <div class="page-header">
        <h5>All Members</h5>
        <div>
            <button class="btn btn-primary btn-sm" v-on:click='actOne'>Active members</button>
            <button class="btn btn-danger btn-sm" v-on:click='actTwo'>In-Active members</button>
        </div>
    </div>
    <div class="bg-white mb-3 p-3">
        <form action="" method="get">
            <div class="row align-items-end">
                <div class="col-sm-6">
                    <input name="q" value="" class="form-control" placeholder="Search by Name, Userid or Email id, Mobile" />
                </div>
                <!-- <div class="col-sm-2">
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
                </div> -->
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
                    <th>Position</th>
                    <th>A/C Activation
                        Date
                    </th>
                    <th>A/c Active</th>
                    <th>Options </th>
                </tr>
            </thead>
            <tbody v-if="isactive ==''">
                <?php
                $sl = 1;
                $where = [];
                $sql = "SELECT * FROM ai_users ";
                if (isset($_GET['q'])) {
                    $q = $_GET['q'];
                    $sql .= "WHERE username LIKE '%$q%' OR id LIKE '%$q%' OR email_id LIKE '%$q%' OR mobile LIKE '%$q%' OR first_name LIKE '%$q%'";
                }
                $sql .= " ORDER BY slno DESC";

                $items = $db->query($sql)->result();

                foreach ($items as $item) {
                    $user = $db->select('ai_users', ["id" => $item->sponsor_id], false, "created DESC")->row();
                ?>
                    <tr>
                        <td><?= $sl++; ?></td>
                        <td>
                            <a href="#">
                                <?= $item->first_name . ' ' . $item->last_name; ?><br>
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
                            <?php } ?>

                        </td>
                        <td>
                            <?= $item->created; ?><br />
                            <?= $item->ac_active_date; ?>
                        </td>

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
            <tbody v-if="isactive == 'inactive'">
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


                        <td><?= $item->ac_active_date; ?></td>

                        <?php
                        if ($item->ac_status == 0) {
                        ?>
                            <td>
                                <i class="bg-danger p-1 text-light">In-Active</i>
                            </td>
                        <?php } ?>
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
            <tbody v-if="isactive == 'active'">
                <?php
                $sl = 1;
                $items = $db->select('ai_users', ['ac_status' => 1], false, "created DESC")->result();
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


                        <td><?= $item->ac_active_date; ?></td>

                        <?php
                        if ($item->ac_status == 1) {
                        ?>
                            <td>
                                <i class="bg-success p-1 text-light">Active</i>
                            </td>
                        <?php } ?>

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
?>
<script>
    new Vue({
        el: '#origin',
        data: {
            isactive: ""
        },
        methods: {
            actOne: function() {
                this.isactive = "active"
            },
            actTwo: function() {
                this.isactive = "inactive"
            }
        }
    })
</script>