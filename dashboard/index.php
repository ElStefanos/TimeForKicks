<?php
include '../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/css/main.css"); ?>">
    <title>Dashboard</title>
    <script src="https://kit.fontawesome.com/9623f60d76.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div class="menu">
            <?php $modules->loadModule('menu-header'); ?>
        </div>
        <div class="ajax-response">
    <i></i><p></p>
</div>
    </header>
    <section class="home">
        <div class="menu">
            <?php $modules->loadModule('menu'); ?>
        </div>
        <section class="page">
            <?php $page = new getPages; ?>
        </section>
    </section>
</body>

</html>