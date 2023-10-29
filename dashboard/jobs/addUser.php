<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;
use admin\adminRegister;

$admin = new adminCheck;

$admin->checkAdmin();


use dataBase\dataBase;

$db = new dataBase;
$error = 0;

if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
    $error = 1;
    $errors['name'] = "Empty fields!";
} else {
    $createUser = new adminRegister($_POST['email'], $_POST['password'], $_POST['username']);
    if($createUser->createUser()) {
        $data['message'] = 'User '. $_POST['username'] . ' created!';
    } else {
        $error = 1;
        $errors['name'] = 'User '. $_POST['username'] . ' exists!';
    }
}

if ($error == 1) {
    $data['success'] = false;
    $data['errors'] = true;
    $data['message'] = $errors['name'];
} else {
    $data['success'] = true;
    $data['errors'] = false;
    
}

echo json_encode($data);