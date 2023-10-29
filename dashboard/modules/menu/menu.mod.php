<?php

use dashboard\{getAssets, getModules, getUserInfo, requestedPage};

$page = new requestedPage;

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();
$page = $page->returnPage();
$assets = new getAssets;
$userInfo = new getUserInfo;
$userInfo = $userInfo->getInfo();
?><script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/menu/css/menu.css"); ?>">
<div class="menu-container">
    <div class="menu-nav-container">
        <div class="menu-nav-item">
            <i class="fa-solid fa-gauge"></i><a href="<?php $assets->getpageLink("dashboard"); ?>">Dashboard</a>
        </div>
        <div class="menu-nav-item">
            <i class="fa-solid fa-file"></i><a href="<?php $assets->getPageLink("sites"); ?>">Sites</a>
        </div>
        <div class="menu-nav-item">
            <i class="fa-solid fa-filter"></i><a href="<?php $assets->getPageLink("filters"); ?>">Products</a>
        </div>
        <div class="menu-nav-item">
            <i class="fa-solid fa-link"></i><a href="<?php $assets->getPageLink("webhooks"); ?>">WebHooks</a>
        </div>
        <div class="menu-nav-item">
            <i class="fa-solid fa-user"></i><a href="<?php $assets->getPageLink("users"); ?>">Users</a>
        </div>
        <div class="menu-nav-item">
            <i class="fa-solid fa-globe"></i><a href="<?php $assets->getPageLink("network"); ?>">Network</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.menu-nav-item').each(function(index) {
        var menu_link = $(this).children('a').attr('href');
        if ("<?php echo $page ?>" == "<?php echo __URL__ . "dashboard?page=dashboard" ?>") {
            $('.menu-nav-item:first').addClass("current");
        } else if ('<?php echo $page ?>'.indexOf(menu_link) >= 0) {
            $(this).addClass("current");
        }
    });
</script>