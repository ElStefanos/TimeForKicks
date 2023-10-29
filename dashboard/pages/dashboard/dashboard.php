<?php

use dashboard\getAssets;
use dashboard\getModules;
use dashboard\getUserInfo;
$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();
?>

<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/pages/dashboard/css/dashboard.css"); ?>">
<section class="dashboard_modules">
    <div class="module">
        <?php $modules->loadModule('stats');  ?>
    </div>

</section>