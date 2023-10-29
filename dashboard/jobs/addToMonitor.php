<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';


use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;



if(!isset($_POST['submit'])) {
    header("Location:".__URL__."/dashboard?page=monitor&error");
    exit();
}


if(!isset($_POST['site']) || $_POST['site'] == "null") {
    header("Location:".__URL__."/dashboard?page=monitor&error_no_site_selected");
    exit();
}

if(!isset($_POST['link']) || str_replace(' ','', $_POST['link']) == '') {
    header("Location:".__URL__."/dashboard?page=monitor&error_no_url_set");
    exit();
}

$mysqli = new dataBase;

$site = $_POST["site"];

$link = $_POST["link"];

$mysqli->insertTable("kicks_monitor", "(`id`, `site`, `link`, `sizes`, `status`)", "(NULL, '$site', '$link', NULL, 1)");

header("Location:".__URL__."/dashboard?page=monitor&success");
exit();