<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// print_r($_GET);
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

if ($action && $id) {
    $item = $db->select("ai_fund_request", ['id' => $id], 1)->row();
    if (is_object($item) && $item->status == 0) {
        if ($action == 'confirm') {
            $userOb = new User();
            if ($item->fund_type == 1) {
                // Credit to fund
                $userOb->creditFund($item->user_id, $item->amount, FUND_DEPOSITE, $id);
            } else {
                $userOb->creditRechargeFund($item->user_id, $item->amount, FUND_TRANSFER, $item->id, "FUND REQUEST");
            }

            $sb = [];
            $sb['status'] = 1;
            $db->update('ai_fund_request', $sb, ['id' => $id], 1);
            session()->set_flashdata('success', 'Fund Request approved successfully');
        } else if ($action == 'decline') {
            $sb = [];
            $sb['status'] = 2;
            $db->update('ai_fund_request', $sb, ['id' => $id], 1);
            session()->set_flashdata('info', 'Fund Request declined successfully');
        }
    } else {
        session()->set_flashdata('error', 'Request already processed');
    }
}
redirect(admin_url('fundmanagement/todays_diposit.php'));
