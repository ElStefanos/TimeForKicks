<?php

use dashboard\{getAssets, getModules, getUserInfo, getPages};
use dataBase\dataBase;
$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
$db = new dataBase;
$sites = $db->grabResultsTable('kicks_indexes', "WHERE `active` = '1'");
use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();
?>
<section class="filters_modules">
    <div class="module">
        <?php
            $modules->loadModule('list_filters');
        ?>
    </div>
</section>