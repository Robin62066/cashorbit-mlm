<?php

include "../../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$menu = 'withdrawal';
if (isset($_GET['act']) && $_GET['act'] == 'del') {
    $id = $_GET['id'];
    $db->delete('ai_withdraw', ['id' => $id]);
    set_flashdata("success", "Property list deleted");
}

$items = $db->select('ai_withdraw', ['status' => 2], false, "id DESC")->result();
include "../common/header.php";

?>

<div id="origin">

    <div class="page-header">

        <h5>Withdrawal Request</h5>

    </div>

    <div class="bg-white p-3">

        <table class="table data-table">

            <thead>

                <tr>

                    <th>Sl</th>

                    <th>Created</th>

                    <th>Amount</th>

                    <th>Net Pay</th>

                    <th>UserInfo</th>

                    <th>Bank A/c Details</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $sl = 1;

                foreach ($items as $item) {

                    $user = $db->select('ai_users', ['id' => $item->user_id], false, "id DESC")->row();





                ?>

                    <tr>

                        <td><?= $sl++; ?></td>



                        <td><?= $item->created; ?></td>

                        <td><?= $item->amount; ?></td>

                        <td><?= $item->amount; ?></td>

                        <td><a href="">

                                <?= $user->username; ?><br>

                                <?= $user->first_name; ?><br>

                                mobile:<?= $user->mobile; ?><br>

                            </a>

                        </td>

                        <td>

                            <?= $user->bank_name; ?><br>

                            <?= $user->bank_ac_number; ?><br>

                            <?= $user->bank_ifsc; ?><br>

                        </td>

                        <?php

                        if ($item->status == 2) {

                        ?>

                            <td>

                                <i class="bg-danger p-1 text-light">Approved</i>

                            </td>

                        <?php }  ?>




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
