<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;
$mysqli = new dataBase;

if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $mysqli->deleteData('kicks_admin', "WHERE `id` = '$id'");
    header("Location:".__URL__."/dashboard?page=users&user=deleted");
    exit();
}

if(isset($_GET['suspend']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $mysqli->updateTable('kicks_admin', "`active` = '0' WHERE `id` = '$id'");
    header("Location:".__URL__."/dashboard?page=users&user=suspended");
    exit();
}

if(isset($_GET['activate']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $mysqli->updateTable('kicks_admin', "`active` = '1' WHERE `id` = '$id'");
    header("Location:".__URL__."/dashboard?page=users&user=activated");
    exit();
}

header("Location:".__URL__."/dashboard?page=users&error");
exit();
?>