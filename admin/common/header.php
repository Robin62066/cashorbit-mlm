<!DOCTYPE html>
<html lang="en">

<head>
    <title>Secure Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300&display=swap" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/style.css'); ?>" />

    <script type="text/javascript" src="<?= base_url('assets/admin/js/jquery-3.2.1.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
    <script src="<?= base_url('assets/admin/js/vue.js'); ?>"></script>
    <script>
        var ApiUrl = '<?= base_url('api.php') ?>';
        var apiUrl = '<?= base_url('api.php') ?>';
        $(document).ready(function() {
            $('.btn-menu').click(function() {
                $('.sidebar').toggle();
            });
        });
    </script>
</head>
<?php
$menu = isset($menu) ? $menu : '';
?>

<body>
    <div class="main-outer">
        <div class="sidebar">
            <div class="userinfo bg-white">
                <img src="<?= admin_url('logo1.jpg') ?>" class="img-fluid circle" />
                <div class="user-details text-dark">
                    Welcome <b>Admin</b> <br />
                    <small><?php echo date("jS M, h:i A"); ?></small><br />
                    <a href="<?= admin_url('index.php') ?>" class="btn btn-light btn-logout">Logout <span class="fa fa-sign-out"></span></a>
                </div>
            </div>
            <ul class="menu">
                <li><a href="<?= admin_url('dashboard.php') ?>"><i class="bi-window-dock"></i> Dashboard </a></li>
                <li><a href="<?= admin_url('recharge-req-hist.php') ?>"><i class="bi-phone"></i> Recharge Report </a></li>
                <li><a href="<?= admin_url('recharged-history.php') ?>"><i class="bi-table"></i> Recharged History </a></li>
                <li><a href="<?= admin_url('boosting-report.php') ?>"><i class="bi-table"></i> Boosting Report </a></li>

                <li class="has-submenu <?= $menu == 'members' ? 'active' : null; ?>"><a href="#"><i class="bi-people"></i> Member History<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('members/all_members.php') ?>"><span class="bi-chevron-right"></span>All Members</a></li>
                        <li><a href="<?= admin_url('members/inactive-members.php') ?>"><span class="bi-chevron-right"></span>Inactive Members </a></li>
                        <li><a href="<?= admin_url('members/today_registration.php') ?>"><span class="bi-chevron-right"></span>Today's Registerd User</a></li>
                        <li><a href="<?= admin_url('members/kyc_update.php') ?>"><span class="bi-chevron-right"></span>KYC Updated</a></li>
                    </ul>
                <li class="has-submenu <?= $menu == 'fundmanagement' ? 'active' : null; ?>"><a href="#"><i class="bi-wallet2"></i> Fund Management<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('fundmanagement/todays_diposit.php') ?>"><span class="bi-chevron-right"></span>Todays Deposit</a></li>
                        <li><a href="<?= admin_url('fundmanagement/diposit_history.php') ?>"><span class="bi-chevron-right"></span> Deposite History</a></li>
                        <li><a href="<?= admin_url('fundmanagement/credit_debit.php') ?>"><span class="bi-chevron-right"></span>Debit/Credit Balance</a></li>
                        <li><a href="<?= admin_url('fundmanagement/credit_debit_report.php') ?>"><span class="bi-chevron-right"></span>Debit/Credit Report</a></li>
                    </ul>
                </li>
                <li class="has-submenu <?= $menu == 'withdrawal' ? 'active' : null; ?>"><a href="#"><i class="bi-piggy-bank"></i> Withdrawal Report<span class="bi-chevron-right"></span></a></a>
                    <ul>
                        <li><a href="<?= admin_url('withdrawal/withdrawal_request.php') ?>"><span class="bi-chevron-right"></span> Withdrawal Request</a></li>
                        <li><a href="<?= admin_url('withdrawal/withdrawal_history.php') ?>"><span class="bi-chevron-right"></span> Withdrawal History</a></li>
                        <li><a href="<?= admin_url('withdrawal/declined_withdrawal.php') ?>"><span class="bi-chevron-right"></span> Declined Withdrawals</a></li>
                    </ul>
                </li>

                <li class="has-submenu <?= $menu == 'cms' ? 'active' : null; ?>"><a href="#"><i class="bi-pc-display"></i> CMS<span class="bi-chevron-right"></span></a>
                    <ul>
                        <?php
                        ?>
                        <li><a href="<?= admin_url('cms/announcement.php') ?>"><span class="bi-chevron-right"></span>Announcement</a></li>
                        <li><a href="<?= admin_url('cms/media_manager.php') ?>"><span class="bi-chevron-right"></span>Media Manager</a></li>
                    </ul>
                </li>

                <!-- <li class="has-submenu <?= $menu == 'supports' ? 'active' : null; ?>"><a href="#"><i class="bi-info-square"></i> Support<span class="bi-chevron-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url('support/pending.php') ?>"><span class="bi-chevron-right"></span> Pending Tickets </a></li>
                        <li><a href="<?= admin_url('support/ticket_history.php') ?>"><span class="bi-chevron-right"></span> Ticket History </a></li>

                    </ul>
                </li> -->
                <li class="has-submenu <?= $menu == 'settings' ? 'active' : null; ?>"><a href="#"><i class="bi-gear"></i> Settings<span class="bi-chevron-right"></span></a>
                    <ul>
                        <li><a href="<?= admin_url('setting/edit_profile.php') ?>"><span class="bi-chevron-right"></span> Edit Profile</a></li>
                        <li><a href="<?= admin_url('setting/general_setting.php') ?>"><span class="bi-chevron-right"></span> General Settings</a></li>
                        <li><a href="<?= admin_url('setting/manage_admin.php') ?>"><span class="bi-chevron-right"></span> Manage Admin</a></li>
                        <li><a href="<?= admin_url('setting/change_password.php') ?>"><span class="bi-chevron-right"></span> Change Password </a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="topbar bg-primary">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col col-sm-4">
                            <button class="btn btn-menu btn-outline-light">
                                <i class="fa fa-navicon"></i>
                                Dashboard
                            </button>
                        </div>

                        <div class="col col-sm-8">
                            <ul class="qmenu">
                                <li><a class="text-white" target="_blank" href="<?= base_url() ?>"><i class="fa fa-desktop"></i> Company </a></li>
                                <li><a class="text-white" href="<?= admin_url('index.php') ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <?php include ROOT_PATH . "admin/common/alert.php" ?>