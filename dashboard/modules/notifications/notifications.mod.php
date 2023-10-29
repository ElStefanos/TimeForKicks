<?php

use dashboard\{getAssets, getUserInfo, getModules};


use admin\adminCheck;

$admin = new adminCheck;
$modules = new getModules;
$admin->checkAdmin();
$assets = new getAssets;
$userInfo = new getUserInfo;
$userInfo = $userInfo->getInfo();
$notifications = new events\notifications;
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/notifications/css/notifications.css"); ?>">
<div class="notification-container hidden">
    <div class="notification-header">
        <h2><span id="total">0</span> Unread notifications</h2>
    </div>
    <div class="notifications-list">
        
    </div>
    <div class="notifications-footer">
        <a href="<?php $assets->getPageLink('notifications');?>"><i class="fa-solid fa-bell"></i>Notification center</a>
    </div>
</div>