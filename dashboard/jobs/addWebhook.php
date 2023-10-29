<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

if (!isset($_POST['add-webhook'])) {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}
else {
    $url = $_POST['url'];
    $site = $_POST['site'];
}

$mysqli = new dataBase;
$mysqli->insertTable('kick_webhooks', "(`type`,`url`,`site`, `status`)", "('discord', '$url','$site', '1')");

header("Location:".__URL__."/dashboard?page=webhooks&webhook=added");
exit();
