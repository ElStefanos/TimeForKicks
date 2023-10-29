<?php

use admin\adminCheck;
use dashboard\{getAssets, getModules, getUserInfo, getPages};
use dataBase\dataBase;
$userInfo = new getUserInfo;
$admin = new adminCheck;
$admin->checkAdmin();
$db = new dataBase;
$filters = $db->grabResultsTable('kicks_filters');
$assets = new getAssets();
?>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/list_filters/css/list_filters.css"); ?>">
<table style="height: 225px; width: 100%;">
<tbody>
<tr>
<td style="width: 70px;">&nbsp;ID</td>
<td style="width: 78px;">&nbsp;INDEX ID</td>
<td style="width: 70px;">&nbsp;GLOBAL</td>
<td style="width: 300.188px;">&nbsp;NAME</td>
<td style="width: 138px;">&nbsp;ACTIVE</td>
<td style="width: 138px;">&nbsp;ACTION</td>
</tr>

<?php
foreach ($filters as $filter) {
    echo '<tr>';
    echo '<td style="width: 64px;">'.$filter['id'].'</td>';
    echo '<td style="width: 64px;">'.$filter['index_id'].'</td>';
    echo '<td style="width: 64px;">'.$filter['is_global'].'</td>';
    echo '<td style="width: 64px;">'.$filter['filter'].'</td>';
    echo '<td style="width: 64px;">'.$filter['status'].'</td>';
    echo '<td style="width: 64px;"><a href = "'.__URL__ .'/dashboard/jobs/deleteFilter.php?delete='.$filter['id'].'"><i class="fa-solid fa-circle-xmark"></i></a></td>';
    echo '</tr>';
}
?>

</tbody>
</table>