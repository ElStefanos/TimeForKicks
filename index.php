<?php
    include './directoryMap.php';
    include __FUNCTIONS__.'autoloader.php';

    use admin\adminCheck;

    $check = new adminCheck;

    $check->getLoginPage();
?>
    