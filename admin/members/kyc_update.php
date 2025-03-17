<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$items = $db->select('ai_users', [], false, "id DESC")->result();
$menu = "members";
include "../common/header.php";

?>

<div id="origin">

    <div class="page-header">

        <h5>KYC Update</h5>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>User Name</th>

                    <th>Adhar Front</th>

                    <th>Adhar Back</th>

                    <th>PAN</th>

                    <th>PAN Number</th>
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

                        <td><?= $item->username; ?></td>

                        <td>

                            <?php

                            if ($item->aadharf != '') {

                            ?>

                                <img src="<?= base_url(upload_dir($item->aadharf)) ?>" width="100" />

                            <?php

                            }

                            ?>

                        </td>

                        <td>

                            <?php

                            if ($item->aadharb != '') {

                            ?>

                                <img src="<?= base_url(upload_dir($item->aadharb)) ?>" width="100" />

                            <?php

                            }

                            ?>

                        </td>

                        <td>

                            <?php

                            if ($item->pan != '') {

                            ?>

                                <img src="<?= base_url(upload_dir($item->pan)) ?>" width="100" />

                            <?php

                            }

                            ?>

                        </td>

                        <td><?= $item->pan_no; ?></td>
                        <td>
                            <?php if ($item->kyc_status == 1) { ?>
                                <i class="bg-success p-1 text-light">Approved</i>
                            <?php } else if ($item->kyc_status == 2) { ?>
                                <i class="bg-danger p-1 text-light">Rejected</i>
                            <?php } else if ($item->kyc_status == 0) { ?>
                                <i class="bg-warning p-1 text-light">Pending</i>
                            <?php } ?>
                        </td>


                        <td>

                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('members/kyc-update-action.php?action=confirm&id=' . $item->id); ?>" class="btn btn-xs btn-primary btn-confirm" data-msg="Are you sure to Confirm?">Approve</a>
                                <a href="<?= admin_url('members/kyc-update-action.php?action=decline&id=' . $item->id); ?>" class="btn btn-xs btn-danger btn-confirm" data-msg="Are you sure to Reject?">Reject</a>
                            </div>
                        </td>

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
