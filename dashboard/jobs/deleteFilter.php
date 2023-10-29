<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

if (!isset($_GET['delete'])) {
    header("Location:".__URL__."/dashboard?page=filters&error=not_selected_filter");
    exit();
}
else {
    $id = $_GET['delete'];
}

$mysqli = new dataBase;

if (!empty($id)) {
    $mysqli->deleteData('kicks_filters', "WHERE `id` = '$id'");
} else {
    header("Location:".__URL__."/dashboard?page=filters&error=not_selected_filter");
    exit();
}

header("Location:".__URL__."/dashboard?page=filters&filter_removed");
exit();
?>