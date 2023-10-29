<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};
use dataBase\dataBase;

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$db = new dataBase;
$sites = $db->grabResultsTable('kicks_indexes', "WHERE `active` = '1'");
?>
<script src="https://kit.fontawesome.com/9623f60d76.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/quick_actions/css/dashboard_quick.css"); ?>">
<div class="quick-container">
    <div class="quick-item">
        <h4>Add new filter</h4>
        <form action="<?php echo __URL__ . '/dashboard/jobs/addFilter.php'; ?>" method="post" class="form">
            <div class="form-item">
                <input type="text" name="filter" placeholder="Ex. Air Jordan 1">
            </div>
            <div class="form-item">
                <label for="type">Select website: </label>
                <select name="type" id="type">
                    <option value="global">Global</option>
                    <?php foreach ($sites as $site) {
                        echo '<option value=' . $site['id'] . '>' . $site['url'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-item">
                <button type="submit" name="add-filter">Add Filter</button>
            </div>
        </form>
    </div>
    <div class="quick-item">
        <h4>Add new website</h4>
        <div class="form">
            <div class="form-item">
                <input id="site" type="text" name="site" placeholder="Ex. kicks.rs" />
            </div>
            <div class="form-item">
            </div>
            <div class="form-item">
                <button name="add-site" onclick="addSite();">Add Site</button>
            </div>
        </div>
    </div>
    <div class="quick-item">
        <h4>Purge cache</h4>
        <form action="<?php echo __URL__ . '/dashboard/jobs/deleteCache.php'; ?>" method="post" class="form">
            <div class="form-item">
                <i class="fa-solid fa-trash" style="font-size: 50px;"></i>
            </div>
            <div class="form-item">
            </div>
            <div class="form-item">
                <button type="submit" name="crawl">Purge</button>
            </div>
        </form>
    </div>
</div>