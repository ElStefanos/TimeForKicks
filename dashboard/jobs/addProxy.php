<?php
include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;
use network\proxy;

$db = new dataBase;
$error = 0;

if (empty($_POST['ip']) || empty($_POST['port']) || empty($_POST['user']) || empty($_POST['password'])) {
    $error = 1;
    $errors['name'] = "Empty fields!";
} else {
    $uname = $_POST['user'];
    $pwd = $_POST['password'];
    $ip = $_POST['ip'];
    $port = $_POST['port'];
    $proxy = new proxy($uname, $pwd, $ip, $port);
    if ($msg = $proxy->addProxy() == 1) {
        $msg = 'Success!';
    } else {
        $errors['name'] = $proxy->testProxy();
        $error = 1;
    }
}

if ($error == 1) {
    $data['success'] = false;
    $data['errors'] = true;
    $data['message'] = $errors['name'];
} else {
    $data['success'] = true;
    $data['errors'] = false;
    $data['message'] = 'Success!';
}

echo json_encode($data);
