<?php
if ($site_live) {
    define('HOSTNAME', '');
    define('USERNAME', '');
    define('PASSWORD', '');
    define('DATABASE', '');
} else {

    define('HOSTNAME', 'localhost');
    define('USERNAME', 'root');
    define('PASSWORD', '');
    define('DATABASE', 'sanjri');
}

define('KYC_PENDING', 0);
define('KYC_PROCESSING', 2);
define('KYC_APPROVED', 1);
define('KYC_REJECTED', 3);

define("WITHDRAWAL_FEE", 10);

define("INCOME_SPONSOR", 'sponsor');
define("INCOME_LEVEL", 'level');
define("INCOME_MATCHING", 'matching');
define("INCOME_AUTOPOOL", 'autopool');
define("INCOME_BOOSTING", 'boosting');
define("FUND_DEPOSITE", 'fund-deposit');
define("FUND_TRANSFER", 'fund-transfer');
define("RECHARGE_CASHBACK", 'recharge');
define("ACCOUNT_TOPUP", 'topup');
define("ACCOUNT_UPGRADE", 'topup-upgrade');
define("INCOME_ROI", "roi");
define("INCOME_ROI_LEVEL", "roi-level");
define("INCOME_ORBIT", "orbit");
define("FAMILY_CLUB ", "family");
define("INCOME_WEEKLY", "weekly");
define("ACCOUNT_BOOSTING", "activate-boosting");
define("INCOME_MAGIC", "magic");

define("WITHDRAW", 'withdraw');

define("P2P", 'p2p');
define("M2F", "m2f");
define("M2R", "m2r");
