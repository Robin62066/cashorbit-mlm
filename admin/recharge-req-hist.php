<?php

include "../config/autoload.php";

if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

if (isset($_GET['act']) && $_GET['act'] == 'del') {

    $id = $_GET['id'];
    $sb = [];
    $sb['status'] = 3;
    $db->update('ai_recharges', $sb, ['id' => $id]);

    set_flashdata("success", "Payment Declined !!");
}

if (isset($_GET['act']) && $_GET['act'] == 'pay') {
    $id = $_GET['id'];
    $data = $db->select("ai_recharges", ["id" => $id], 1)->row();
    $userOb = new User();
    $res = $userOb->doRecharge($data->mobile, $data->amount, $data->operator, $data->id);
    if (is_object($res)) {
        if ($res->Status == "SUCCESS") {
            if ($res->MSG) {
                $sb = [];
                $sb['OPID'] = $res->OPID;
                $sb['RBID'] = $res->RBID;
                $sb['MSG']  = $res->MSG;
                $sb['status'] = 1;
                $db->update('ai_recharges', $sb, ['id' => $id]);

                $userOb->creditRechargeCommission($data->user_id, $data->amount, $data->id);
                set_flashdata("success", "Recharge successful");
            }
        } else if ($res->Status == "FAILED") {
            set_flashdata("danger", $res->MSG);
        }
        $sb = [];
        $sb['status'] = 1;
        $db->update('ai_recharges', $sb, ['id' => $id]);
        set_flashdata("success", "Recharge saved successful");
    } else {
        set_flashdata("danger", "Rechare services failed, Contact support team.");
    }
}
$sql = "SELECT * FROM ai_recharges WHERE status IN (2,3) ORDER BY id DESC";

$items = $db->query($sql)->result();
include "common/header.php";

?>

<div id="origin">

    <div class="page-header">

        <h5>Recharge Request History</h5>

    </div>
    <div class="bg-white p-3">

        <table class="table data-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>User Info</th>
                    <th>Amount</th>
                    <th>Mobile Number</th>
                    <th>Operator</th>
                    <th>Request Date</th>
                    <th>status</th>
                    <th>Action</th>

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
                        <td>
                            <a href="#">
                                <?= $user->first_name; ?><br>
                                <?= $user->username; ?><br>
                                <?= $user->mobile; ?><br>
                                <?= $user->email_id; ?>
                            </a>
                        </td>
                        <td><?= $item->amount; ?></td>
                        <td><?= $item->mobile; ?></td>
                        <td><?= $item->operator; ?></td>
                        <td><?= $item->recharge_date; ?></td>
                        <td>
                            <?php
                            if ($item->status == 2) {   ?>
                                <i class="bg-warning p-1 text-light">Panding</i>
                            <?php } else if ($item->status == 3) {   ?>
                                <i class="bg-danger p-1 text-light">rejected</i>
                            <?php } else { ?>
                                <i class="bg-success p-1 text-light">Paid</i>
                            <?php } ?>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?= admin_url('recharge-req-hist.php?id=' . $item->id); ?>&act=pay" class="btn btn-xs btn-primary btn-confirm" data-msg="Are you sure to Proceed?">Pay</i></a>
                                <?php if ($item->status != 3) { ?>
                                    <a href="<?= admin_url('recharge-req-hist.php?id=' . $item->id); ?>&act=del" class="btn btn-xs btn-danger btn-delete"><i class="bi-trash">Reject</i></a>
                                <?php } ?>
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

include "common/footer.php";
