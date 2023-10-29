<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

if (!isset($_GET['delete'])) {
    header("Location:".__URL__."/dashboard?page=network&error=not_selected_proxy");
    exit();
}
else {
    $id = $_GET['delete'];
}

$mysqli = new dataBase;

if (!empty($id)) {
    $mysqli->deleteData('kicks_networking', "WHERE `id` = '$id'");
} else {
    header("Location:".__URL__."/dashboard?page=network&error=not_selected_proxy");
    exit();
}

header("Location:".__URL__."/dashboard?page=network&proxy_removed");
exit();
?>