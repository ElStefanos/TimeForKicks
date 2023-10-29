<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

if (!isset($_POST['purge-cache'])) {
    $cache = scandir(__CACHE__);
    unset($cache[0]);
    unset($cache[1]);

    $cache= array_values($cache);

    foreach ($cache as $key => $value) {
        $files = scandir(__CACHE__.$value);
        unset($files[0]);
        unset($files[1]);
    
        $files= array_values($files);
        foreach($files as $file) {
            unlink(__CACHE__.$file.DIRECTORY_SEPARATOR.$file);
            rmdir(__CACHE__.$file);
        }
    }
}

header("Location:".__URL__."/dashboard?page=dashboard&cache-purged");
exit();
?>