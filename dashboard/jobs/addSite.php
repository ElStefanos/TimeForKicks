<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;

$admin = new adminCheck;

$admin->checkAdmin();

use dataBase\dataBase;

use identify\identifySite;
use dashboard\{getAssets, getModules, getUserInfo, requestedPage};

$modules = new getAssets;

if (!session_status()) {
    session_start();
}

$user = $_SESSION['username'];

if (!isset($_POST['site'])) {
    echo 'ERROR';
}

$site  = $_POST['site'];
$identify = new identifySite($site);

$code = $identify->identify();


$mysqli = new dataBase;


$errors = [];
$data = [];

$narrow = 0;
$success = 0;

$message = "Ooops! Something went wrong!";

if ($code == 0) {
    $type = 'hard';
    $mysqli->insertTable('kicks_indexes', "(`url`, `number_of_links`, `type`, `active`, `created_by`)", "('$site', NULL, '$type', '1', '$user')");
    $success = 1;
    $narrow = 1;
}

if ($code == 1) {
    $type = 'easy';
    $mysqli->insertTable('kicks_indexes', "(`url`, `number_of_links`, `type`, `active`, `created_by`)", "('$site', NULL, '$type', '1', '$user')");
    $success = 1;
}

if ($code == 2) {
    $type = 'medium';
    $mysqli->insertTable('kicks_indexes', "(`url`, `number_of_links`, `type`, `active`, `created_by`)", "('$site', NULL, '$type', '1', '$user')");
    $success = 1;
    $narrow = 1;
}

if ($code == 3) {
    $errors['name'] = "ERROR: Site not compatible!";
}

$url = __URL__ . '/dashboard/jobs/addNarrowing.php?site=' . $site;


if ($narrow == 1) {
    $form = "<form class='rendered-form' method='post' action='" . $url . "'>
<div class=''>
    <h1 access='false' id='control-6267839'>This site is to big. Please add categories to search.<br></h1></div>
<div class='formbuilder-text form-group field-category'>
    <label for='category' class='formbuilder-text-label'>Type: " . $type . "</label>
    <input type='text' placeholder='Add one or more categories and separate them with space. Ex. (sneaker shoe man mans women...)' class='form-control' name='category' access='false' maxlength='255' id='category'>
</div>
<div class=''>
    <p access='false' id='control-4278282'>*Be careful if you choose bigger number there is chance most of the products will not be found!
        <br>
    </p>
</div>
<div class='formbuilder-number form-group field-minMatch'>
    <label for='minMatch' class='formbuilder-number-label'>Minimum number of matching terms
        <br>
    </label>
    <input type='number' class='form-control' name='minMatch' access='false' value='1' min='1' id='minMatch'>
</div>
<div class='formbuilder-button form-group field-add-narrowing'>
    <button type='submit' class='btn-primary btn' name='add-narrowing' access='false' style='primary' id='add-narrowing'>Add Narrowing
        <br>
    </button>
</div>
</form>";
}



if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = true;
    $data['narrow'] = false;
    $data['message'] = $errors['name'];
} else if ($success == 1 && $narrow == 0) {
    $data['success'] = true;
    $data['errors'] = false;
    $data['narrow'] = false;
    $data['message'] = 'Success!';
} else if ($narrow == 1 && $success == 1) {
    $data['success'] = true;
    $data['errors'] = false;
    $data['narrow'] = true;
    $data['message'] = $form;
} else {
    $data['success'] = false;
    $data['errors'] = true;
    $data['narrow'] = false;
    $data['message'] = $message;
}

echo json_encode($data);
