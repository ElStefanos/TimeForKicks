<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use webhook\webhookDiscord;

$payload = array("Test 1", "Test 2", "Test 3");




if (!isset($_GET['id'])) {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}
else {
    $id = $_GET['id'];
}


if (!empty($id)) {
    
    $webhook = new webhookDiscord($payload);

    $webhook->sendHookSite($id);

} else {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}

header("Location:".__URL__."/dashboard?page=webhooks&webhook_tested");
exit();
?>