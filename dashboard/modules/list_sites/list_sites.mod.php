<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};
use dataBase\dataBase;

$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$db = new dataBase;
$sites = $db->grabResultsTable('kicks_indexes');
$assets = new getAssets();
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/list_sites/css/list_sites.css"); ?>">

<div class="sites-overview">
    <?php

    foreach ($sites as $key => $value) {
        $name = $value['url'];
        $id = $value['id'];
        $numLinks = $value['number_of_links'];
        $type = $value['type'];
        $parseTime = $value['parse_time'];
        $creator  = $value['created_by'];
        $status = $value['active'];
        switch ($status) {
            case '0':
                $action = 1;
                $status = 'Paused';
                break;

            default:
                $action = 0;
                $status = 'Active';
                break;
        }

        if ($value['active'] == 1) {
            echo " <div class='container'> ";
            echo "<div class='sites-item'>";
        } else {
            echo " <div class='container paused'> ";
            echo "<div class='sites-item paused'>";
        }


        echo "<a href='http://$name'>
                <h3>$name</h3>
                </a>
                <i class='action fa-solid fa-circle-info' id='info' onClick='openInfo(" . $id . ");'></i>";
        if ($value['active'] == 1) {
            echo "<i class='action fa-solid fa-pause' id='pause' onClick='pauseSite(" . $id . "," . $action . ");'></i>";
        } else {
            echo "<i class='action fa-solid fa-play' id='play' onClick='pauseSite(" . $id . "," . $action . ");'></i>";
        }
        echo "<a href = '" . __URL__ . "/dashboard/jobs/deleteSite.php?delete=" . $id . "' class='action'><i class='fa-regular fa-trash-can'></i></a>
            </div>
            <div class='site-info' id='" . $id . "'>
                <p>Status: $status</p>
                <p>Number of Links: $numLinks</p>
                <p>Type: $type</p>
                <p>Parse Time: $parseTime (seconds)</p>
                <p>Creator: $creator</p>
            </div>
            </div>
        ";
    } //<i class="fa-thin fa-trash fa-shake"></i>
    ?>
    <script src="<?php $assets->getAssetsLink("/dashboard/js/sites/openInfo.js"); ?>"></script>
    <script src="<?php $assets->getAssetsLink("/dashboard/js/sites/pauseSite.js"); ?>"></script>
</div>