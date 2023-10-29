<?php

use dashboard\{getAssets, getModules, getUserInfo, getPages};

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();
?>

<section class="sites_modules">
    <div class="module">
        <?php
            $modules->loadModule('list_sites');
        ?>
    </div>
</section>