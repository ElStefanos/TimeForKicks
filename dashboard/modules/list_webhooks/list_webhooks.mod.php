<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};
use dataBase\dataBase;

$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$db = new dataBase;
$webhooks = $db->grabResultsTable('kick_webhooks');
$assets = new getAssets();
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/list_webhooks/css/list_webhooks.css"); ?>">


<?php
foreach ($webhooks as $webhook) {
    $id = $webhook['id'];
    $url = $webhook['url'];
    $site = $webhook['site'];
    $status = $webhook['status'];
    switch ($status) {
        case 0:
            $status = 'Suspended';
            break;

        default:
            $status = 'Active';
            break;
    }
    echo '<div class="container ' . $status . '">
    <p>' . $site . '</p>
    <p>' . $url . '</p>
    <p>' . $status . '</p>
    <div class="actions"><a href = "' . __URL__ . '/dashboard/jobs/deleteWebhook.php?delete=' . $id . '"><i class="fa-regular fa-trash-can"></i></a><a href = "' . __URL__ . '/dashboard/jobs/testWebhook.php?id=' . $site . '"><i class="fa-solid fa-vials" style="color: white;"></i></a></div>
    </div>';
}
?>