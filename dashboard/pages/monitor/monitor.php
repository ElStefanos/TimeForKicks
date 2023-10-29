<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo};
use database\dataBase;

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$mysqli = new dataBase;
$monitored = $mysqli->grabResultsTable("kicks_monitor");


?>

<link rel="stylesheet" href="<?php $assets->getAssetsLink('/dashboard/pages/monitor/css/monitor.css'); ?>">
<section class="monitor">

    <h3>Add new product to monitor</h3>

    <form class="addProduct" action="<?php echo __URL__ . '/dashboard/jobs/addToMonitor.php'; ?>" method="post">
        <select id="sites" name="site">
            <option value="null">Select website</option>
            <option value="very">Very</option>
            <option value="site1">Site 2</option>
            <option value="site1">Site 3</option>
            <option value="site1">Site 4</option>
            <option value="site1">Site 5</option>
        </select>

        <input type="text" id="link-input" name="link" placeholder="Full URL of the product...">

        <button type="submit" name="submit" id="addToMonitor">Add to monitor</button>
    </form>

    <h3>List of products being monitored</h3>

    <div class="products">
        <div class="product">
            <h4 class="item">Site</h4>
            <h4 class="item">URL</h4>
            <h4 class="item actions">Actions</h4>
        </div>

        <?php foreach($monitored as $key => $value): ?>
        <div class="product">
            <h4 class="item"><?php echo $value['site'] ?></h4>
            <a class="item" href="<?php echo $value['link'] ?>" target="_blank"><?php echo $value['link'] ?></a>
            <h4 class="item actions">Actions</h4>
        </div>
        <?php endforeach; ?>
    </div>


</section>