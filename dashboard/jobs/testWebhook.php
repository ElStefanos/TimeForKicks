<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use webhook\webhookDiscord;


if (!isset($_GET['id'])) {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}
else {
    $id = $_GET['id'];
}


if (!empty($id)) {
    
    $webhook = new webhookDiscord("https://test.com", "210,99$");

    $webhook->sendHookSite($id);

} else {
    header("Location:".__URL__."/dashboard?page=webhooks&error=not_selected_webhook");
    exit();
}

header("Location:".__URL__."/dashboard?page=webhooks&webhook_tested");
exit();
?>