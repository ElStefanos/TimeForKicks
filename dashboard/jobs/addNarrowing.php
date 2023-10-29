<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;


if (!isset($_POST['add-narrowing'])) {
    header("Location:".__URL__."/dashboard?page=dashboard&error=not_selected_filter");
    exit();
}
else {
    $cat = $_POST['category'];
    $min = $_POST['minMatch'];
    $site = $_GET['site'];
}

$mysqli = new dataBase;


$mysqli->insertTable('kicks_narrowing', "(`id`, `category`, `minimum_match`, `url`)", "(NULL, '$cat', '$min', '$site')");


header("Location:".__URL__."/dashboard?page=dashboard&narrowing=added");
exit();
