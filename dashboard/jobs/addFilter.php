<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;
use filters\applyFilters;


if (!isset($_POST['add-filter'])) {
    header("Location:".__URL__."/dashboard?page=dashboard&error=not_selected_filter");
    exit();
}
else {
    $type = $_POST['type'];
    $name = $_POST['filter'];
    $filter = new applyFilters($name);
    $name = $filter->createFilter();
}

$mysqli = new dataBase;

if ($type == 'global') {
    $mysqli->insertTable('kicks_filters', "(`index_id`, `is_global`, `filter`, `status`)", "(NULL, '1', '$name', '1')");
} else {
    $mysqli->insertTable('kicks_filters', "(`index_id`, `is_global`, `filter`, `status`)", "('$type', NULL, '$name', '1')");
}

header("Location:".__URL__."/dashboard?page=filters&filter=added");
exit();
?>



