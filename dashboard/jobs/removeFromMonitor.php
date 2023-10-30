<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';


use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

$mysqli = new dataBase();

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $mysqli->deleteData('kicks_monitor', "WHERE `id` = '$id';");
    header("Location:".__URL__."/dashboard?page=monitor&success");
    exit();
}

header("Location:".__URL__."/dashboard?page=monitor&error_no_site_selected");
exit();