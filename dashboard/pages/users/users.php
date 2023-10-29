<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$formatUsers = new admin\loadUsers;
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/pages/users/css/users.css"); ?>">

<div class="wrapper">
    <div class="user-list">
        <span class="user">
            <h4>Username</h4>
            <h4>Email</h4>
            <h4 class="actions">Actions</h4>
        </span>
        <?php
            $formatUsers->formatUsers();
        ?>
    </div>
    <div class="create-user">
        <h4>Create User</h4>
        <input type="username" name="username" placeholder="Username" id="user">
        <input type="email" name="email" placeholder="Email" id="email">
        <input type="password" name="password" placeholder="Password" id="password">
        <button name="createUser" onClick="createUser();">Create User</button>
    </div>
</div>
<script src="<?php $assets->getAssetsLink("/dashboard/js/users/createUser.js"); ?>"></script>