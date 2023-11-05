<?php
include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use events\notifications;
$notifications = new notifications;
echo "<h2>Important</h2>";
$notifications->getNotifications(2);
echo "<h2>Notifications</h2>";
$notifications->getNotifications(1, 20);
echo "<h2>Old Notifications</h2>";
$notifications->getNotifications(0, 10);

