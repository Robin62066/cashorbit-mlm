<?php

include "../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

include "common/header.php";
$users = $db->select('ai_users', [])->result();
$ac_users = $db->select('ai_users', ['ac_status' => 1])->result();
$not_active = $db->select('ai_users', ['ac_status' => 0])->result();
$sql = "SELECT * FROM ai_users WHERE DATE(created) = CURDATE() ORDER BY id DESC";
$items = $db->query($sql)->result();
$sql = "SELECT * FROM ai_users WHERE DATE(ac_active_date) = CURDATE() ORDER BY id DESC";
$item1 = $db->query($sql)->result();

?>

<div class="page-header">

    <h5>Dashboard</h5>

</div>

<div id="origin" class="dashboard text-white">

    <?php include "common/alert.php"; ?>

    <div class="row g-2">

        <div class="col-sm-3">

            <div class="box bg-primary border-0">

                <div class="p-3 d-flex justify-content-between align-items-center">

                    <div>

                        <h6>Total Users</h6>

                        <h2 class="m-0"><?= count($users);  ?></h2>

                    </div>

                    <div>

                        <i class="bi-person fa-3x"></i>

                    </div>

                </div>

                <div class="box-footer p-2 box-footer-dark">

                    <a href="<?= admin_url('members/all_members.php') ?>" class="btn btn-sm btn-outline-light">View More</a>

                </div>

            </div>

        </div>
        <div class="col-sm-3">

            <div class="box bg-primary border-0">

                <div class="p-3 d-flex justify-content-between align-items-center">

                    <div>

                        <h6>Active User</h6>

                        <h2 class="m-0"><?= count($ac_users);  ?></h2>

                    </div>

                    <div>

                        <i class="bi-person fa-3x"></i>

                    </div>

                </div>

                <div class="box-footer p-2 box-footer-dark">

                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>

                </div>

            </div>

        </div>
        <div class="col-sm-3">

            <div class="box bg-primary border-0">

                <div class="p-3 d-flex justify-content-between align-items-center">

                    <div>

                        <h6>Today Registration.</h6>

                        <h2 class="m-0"><?= count($items);  ?></h2>

                    </div>

                    <div>

                        <i class="bi-person fa-3x"></i>

                    </div>

                </div>

                <div class="box-footer p-2 box-footer-dark">

                    <a href="<?= admin_url('members/today_registration.php') ?>" class="btn btn-sm btn-outline-light">View More</a>

                </div>

            </div>

        </div>
        <div class="col-sm-3">

            <div class="box bg-primary border-0">

                <div class="p-3 d-flex justify-content-between align-items-center">

                    <div>

                        <h6>Today Activation</h6>

                        <h2 class="m-0"><?= count($item1);  ?></h2>

                    </div>

                    <div>

                        <i class="bi-person fa-3x"></i>

                    </div>

                </div>

                <div class="box-footer p-2 box-footer-dark">

                    <a href="<?= admin_url('members/today_registration.php') ?>" class="btn btn-sm btn-outline-light">View More</a>

                </div>

            </div>

        </div>
        <div class="col-sm-3">

            <div class="box bg-primary border-0">

                <div class="p-3 d-flex justify-content-between align-items-center">

                    <div>

                        <h6>Pending Users</h6>

                        <h2 class="m-0"><?= count($not_active);  ?></h2>

                    </div>

                    <div>

                        <i class="bi-person fa-3x"></i>

                    </div>

                </div>

                <div class="box-footer p-2 box-footer-dark">

                    <a href="#" class="btn btn-sm btn-outline-light">View More</a>

                </div>

            </div>

        </div>

    </div>




</div>

<?php

include "common/footer.php";
