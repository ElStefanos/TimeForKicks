<?php

use dashboard\{getAssets, getUserInfo, getModules};


use admin\adminCheck;

$admin = new adminCheck;
$modules = new getModules;
$admin->checkAdmin();
$assets = new getAssets;
$userInfo = new getUserInfo;
$userInfo = $userInfo->getInfo();
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/menu-header/css/menu-header.css"); ?>">
<div class="menu-header">
    <h3>Welcome, <?php echo $userInfo['username'] ?>!</h3>
    <div class="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="menu-header-wrapper">
        <div class="menu-header-item">
            <i id="quick-actions"></i>
            <div class="quick-actions-container hidden"> <?php $modules->loadModule('quick_actions'); ?></div>
        </div>
        <div class="menu-header-item" id="unread">
            <i id="notification"><span id="notifications-indicator"></span></i>
            <div class="notifications"><?php $modules->loadModule("notifications"); ?></div>
        </div>
        <div class="menu-header-item">
            <a href="<?php $assets->getAssetsLink("/dashboard/jobs/signOut.php"); ?>"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        </div>
    </div>
</div>

<script src="<?php $assets->getAssetsLink("/dashboard/js/notifications/open-notifications.js"); ?>"></script>
<script src="<?php $assets->getAssetsLink("/dashboard/js/notifications/unread-notifications.js"); ?>"></script>
<script src="<?php $assets->getAssetsLink("/dashboard/js/notifications/read-notifications.js"); ?>"></script>
<script src="<?php $assets->getAssetsLink("/dashboard/js/quick_actions/open-quick.js"); ?>"></script>
<script src="<?php $assets->getAssetsLink("/dashboard/js/quick_actions/add-site.js"); ?>"></script>
<script src="<?php $assets->getAssetsLink("/dashboard/js/menu/open-menu.js"); ?>"></script>