<?php
if (isset($_SESSION['error_msg'])) {
?>
    <div class="alert alert-danger"><?= $_SESSION['error_msg']; ?></div>
<?php
    unset($_SESSION['error_msg']);
}
if (isset($_SESSION['success_msg'])) {
?>
    <div class="alert alert-success"><?= $_SESSION['success_msg']; ?></div>
<?php
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['info_msg'])) {
?>
    <div class="alert alert-info"><?= $_SESSION['info_msg']; ?></div>
<?php
    unset($_SESSION['info_msg']);
}
if (isset($_SESSION['primary_msg'])) {
?>
    <div class="alert alert-primary"><?= $_SESSION['primary_msg']; ?></div>
<?php
    unset($_SESSION['primary_msg']);
}
if (isset($_SESSION['danger_msg'])) {
?>
    <div class="alert alert-danger"><?= $_SESSION['danger_msg']; ?></div>
<?php
    unset($_SESSION['danger_msg']);
}
if (isset($_SESSION['warning_msg'])) {
?>
    <div class="alert alert-warning"><?= $_SESSION['warning_msg']; ?></div>
<?php
    unset($_SESSION['warning_msg']);
}
$msg_success = flashdata('success');
$msg_info = flashdata('info');
$msg_danger = flashdata('danger');
$msg_warning = flashdata('warning');
$msg_dark = flashdata('dark');
$msg_primary = flashdata('primary');
$msg = '';
if ($msg_success != '') $msg = '<div class="alert alert-success">' . $msg_success . '</div>';
if ($msg_info != '') $msg = '<div class="alert alert-info">' . $msg_info . '</div>';
if ($msg_danger != '') $msg = '<div class="alert alert-danger">' . $msg_danger . '</div>';
if ($msg_warning != '') $msg = '<div class="alert alert-warning">' . $msg_warning . '</div>';
if ($msg_dark != '') $msg = '<div class="alert alert-dark">' . $msg_dark . '</div>';
if ($msg_primary != '') $msg = '<div class="alert alert-primary">' . $msg_primary . '</div>';
echo $msg;
?>