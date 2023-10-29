<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminLogin;

if (isset($_POST['login'])) {
    $admin = new adminLogin($_POST['email'], $_POST['password']);
} else {
    header("Location: " . __URL__);
    exit();
}
