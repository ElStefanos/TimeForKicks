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

<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/pages/webhooks/css/webhooks.css"); ?>">
<section class="webhooks_modules">
    <div class="module">
        <form action="<?php echo __URL__ . '/dashboard/jobs/addWebhook.php'; ?>" method="post" class="form-hook">
            <h4>Add Webhook</h4>
            <div class="form-item">
                <input type="text" name="url" placeholder="enter Webhook URL">
            </div>
            <div class="form-item">
                <label for="type">Select webhook type: </label>
                <select name="site" id="type">
                    <option value="global">global</option>
                    <?php
                        foreach ($sites as $key => $value) {
                            echo "<option value='".$value['url']."'>".$value['url']."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-item">
                <button type="submit" name="add-webhook">Add Webhook</button>
            </div>
        </form>
        <?php
        $modules->loadModule('list_webhooks');
        ?>
    </div>
</section>