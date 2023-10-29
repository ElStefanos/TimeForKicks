<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

if(isset($_POST['crawl'])) {
    
    include __SRC__."jobs".DIRECTORY_SEPARATOR."crawler.php";

} else {
    header("Location: ".__URL__."/dashboard?page=dashboard&error=failed_crawler");
    exit();   
}
header("Location:".__URL__."/dashboard?page=dashboard&crawler_success");
exit();
?>