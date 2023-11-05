<?php

echo __DIR__."\n";

if(file_exists('./directoryMap.php')) {
    include './directoryMap.php';
} else {
    include '../../directoryMap.php';
}


include __FUNCTIONS__ . 'cacheControl.php';
include __FUNCTIONS__ . 'autoloader.php';

use dataBase\dataBase;


$dataBase = new dataBase;

$easySites = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'easy';");
$mediumSites = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'medium';");
$hardSites = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'hard';");

echo "Started cache control job....\n";
echo date('l jS \of F Y h:i:s A');
echo "\n\nStarted cache control for hard sites....\n\n";

cacheControl($hardSites, 7200);

echo "\nFinished cache control for hard sites....\n";

echo "\nStarted cache control for medium sites....\n\n";

cacheControl($mediumSites, 1800);

echo "\nFinished cache control for medium sites....\n";
echo "\nStarted cache control for easy sites....\n\n";

cacheControl($easySites, 100);

echo "\nFinished cache control for easy sites....\n\n";
echo "Finished cache control job....\n";
echo date('l jS \of F Y h:i:s A');
echo "\n";