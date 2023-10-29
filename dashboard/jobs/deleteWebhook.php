<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

if (!isset($_GET['delete'])) {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}
else {
    $id = $_GET['delete'];
}

$mysqli = new dataBase;

if (!empty($id)) {
    $mysqli->deleteData('kick_webhooks', "WHERE `id` = '$id'");
} else {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}

header("Location:".__URL__."/dashboard?page=webhooks&webhook_removed");
exit();
?>