<?php
session_start();
$site_live = true;
$config = [];

if ($site_live) {
    // error_reporting(E_ERROR);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $config['base_url'] = 'https://admin.cashorbit.net/';
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $config['base_url'] = 'http://localhost/sanjri/';
}
$config['admin_folder']  = 'admin';
$config['upload_folder'] = 'assets/uploads';
