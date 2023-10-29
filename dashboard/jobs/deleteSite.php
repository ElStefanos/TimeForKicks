<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

if (!isset($_GET['delete'])) {
    header("Location:".__URL__."/dashboard?page=sites&error=not_selected_site");
    exit();
}
else {
    $id = $_GET['delete'];
}

$mysqli = new dataBase;

if (!empty($id)) {
    $mysqli->deleteData('kicks_indexes', "WHERE `id` = '$id'");
} else {
    header("Location:".__URL__."/dashboard?page=sites&error=not_selected_site");
    exit();
}

header("Location:".__URL__."/dashboard?page=sites&site_removed");
exit();
?>