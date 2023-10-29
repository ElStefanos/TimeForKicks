<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

use identify\identifySite;
use dashboard\{getAssets, getModules, getUserInfo, requestedPage};

$modules = new getAssets;

if (!session_status()) {
    session_start();
}

$user = $_SESSION['username'];

if (!isset($_POST['site'])) {
    echo 'ERROR';
}

$site  = $_POST['site'];
$status  = $_POST['action'];


$mysqli = new dataBase;

$message = "Ooops! Something went wrong!";

if ($mysqli->updateTable('kicks_indexes', "`active` = '$status' WHERE `id` = '$site'")) {
    $data['success'] = true;
    $data['errors'] = false;
    $data['message'] = 'Success';
} else {
    $data['success'] = false;
    $data['errors'] = true;
    $data['narrow'] = false;
    $data['message'] = $message;
}

echo json_encode($data);
