<?php
include "../../config/autoload.php";
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

// print_r($_GET);
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

if ($action && $id) {
    $item = $db->select("ai_users", ['id' => $id], 1)->row();
    if (is_object($item)) {
        if ($action == 'confirm') {

            $sb = [];
            $sb['kyc_status'] = 1;
            $db->update('ai_users', $sb, ['id' => $id], 1);
            session()->set_flashdata('success', 'KYC update  successfully');
        } else if ($action == 'decline') {
            $sb = [];
            $sb['kyc_status'] = 2;
            $db->update('ai_users', $sb, ['id' => $id], 1);
            session()->set_flashdata('info', 'KYC declined successfully');
        }
    } else {
        session()->set_flashdata('error', 'Request already processed');
    }
}
redirect(admin_url('members/kyc_update.php'));
