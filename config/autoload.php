<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . "../init.php";

include_once "config.php";
include_once "constant.php";
include_once "session.php";
include_once "database.php";
include_once "functions.php";
include_once "restapi.class.php";

$path = ROOT_PATH . 'common';
$files = scandir($path);

foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;
    $filePath = $path . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) {
        require_once $filePath;
    }
}

$db = new Database(DATABASE, USERNAME, PASSWORD, HOSTNAME);
function db_connect()
{
    $db = Database::instance();
    return $db;
}
