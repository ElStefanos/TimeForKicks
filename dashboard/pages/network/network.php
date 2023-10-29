<?php

use dashboard\getAssets;
use dashboard\getModules;
use dashboard\getUserInfo;
use admin\adminCheck;
use database\dataBase;
use network\networkStats;
use network\network;

$modules = new getModules;
$assets = new getAssets;
$userInfo = new getUserInfo;

$admin = new adminCheck;

$admin->checkAdmin();

$networkDaily = new networkStats;
$networkLast = new networkStats;
$proxyList = new network(__URL__);
$proxyList = $proxyList->proxyList;
$networkLast->lastNetworkStats();
$networkDaily->dailyStats();

$graphs = $networkDaily->dailyStatsOverall();

?>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    am5.ready(function() {
        var root = am5.Root.new("bandwithGraph");
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        root.dateFormatter.setAll({
            dateFormat: "yyyy",
            dateFields: ["valueX"]
        });

        var data = [
            <?php
            foreach ($graphs as $key => $value) {
                $value['bandwith'] = round($value['bandwith'] / pow(1024, 3), 2);
                echo '{date: "' . $key . '", value: ' . $value['bandwith'] . '},';
            }
            ?>
        ];



        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            focusable: true,
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));

        var easing = am5.ease.linear;


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0.1,
            groupData: false,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {

            }),
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.2,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));


        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.LineSeries.new(root, {
            minBulletDistance: 10,
            connect: false,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                pointerOrientation: "horizontal",
                labelText: "{valueY}GB"
            })
        }));

        series.fills.template.setAll({
            fillOpacity: 0.2,
            visible: true
        });

        series.strokes.template.setAll({
            strokeWidth: 2
        });


        // Set up data processor to parse string dates
        // https://www.amcharts.com/docs/v5/concepts/data/#Pre_processing_data
        series.data.processor = am5.DataProcessor.new(root, {
            dateFormat: "yyyy-MM-dd",
            dateFields: ["date"]
        });

        series.data.setAll(data);

        series.bullets.push(function() {
            var circle = am5.Circle.new(root, {
                radius: 4,
                fill: root.interfaceColors.get("background"),
                stroke: series.get("fill"),
                strokeWidth: 2
            })

            return am5.Bullet.new(root, {
                sprite: circle
            })
        });


        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            xAxis: xAxis,
            behavior: "none"
        }));
        cursor.lineY.set("visible", false);

        // add scrollbar
        chart.set("scrollbarX", am5.Scrollbar.new(root, {
            orientation: "horizontal"
        }));


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        chart.appear(1000, 100);

    });
</script>
<section class="network">
    <div class="overview">
        <span>
            <h3>Daily stats</h3>
            <div class="container">
                <div class="stat-item">
                    <h4>Estimated Bandwith Usage</h4>
                    <p>
                        <?php
                        echo $networkDaily->totalEstimatedBandwith;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Requests today</h4>
                    <p>
                        <?php
                        echo $networkDaily->totalRequests;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Succesfull requests</h4>
                    <p>
                        <?php
                        echo $networkDaily->successfullRequests;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Errors today</h4>
                    <p>
                        <?php
                        echo $networkDaily->errors;
                        ?>
                    </p>
                </div>
            </div>
        </span>
        <span>
            <h3>Last query stats</h3>
            <div class="container">
                <div class="stat-item">
                    <h4>Estimated Bandwith Usage</h4>
                    <p>
                        <?php
                        echo $networkLast->totalEstimatedBandwith;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Requests</h4>
                    <p>
                        <?php
                        echo $networkLast->totalRequests;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Succesfull requests</h4>
                    <p>
                        <?php
                        echo $networkLast->successfullRequests;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Errors</h4>
                    <p>
                        <?php
                        echo $networkLast->errors;
                        ?>
                    </p>
                </div>
                <div class="stat-item">
                    <h4>Avg. Response Time</h4>
                    <p>
                        <?php
                        echo $networkLast->avgTime;
                        ?>
                        s</p>
                </div>
            </div>
        </span>
    </div>
    <div class="proxies">
        <h3>Proxy list</h3>
        <div class="proxy-list">
            <span class='proxy'>
                <h4>Ip address</h4>
                <h4>Port</h4>
                <h4>Username:Password</h4>
                <h4>Actions</h4>
            </span>
            <?php
            foreach ($proxyList as $key => $value) {
                $id = $value['id'];
                echo "<span class='proxy'>
                    <p>" . $value['ip'] . "</p><p>" . $value['port'] . "</p><p>" . $value['username_password'] . "</p>
                    <a href = '" . __URL__ . "/dashboard/jobs/deleteProxy.php?delete=" . $id . "' class='action'><i class='fa-regular fa-trash-can'></i></a>
                </span>";
            }
            ?>
        </div>
        </a>
        <h3>Add new proxy</h3>
        <div class="add-proxy">
            <input type="text" id='proxy' placeholder="Ip address">
            <input type="text" id='port' placeholder="Port">
            <input type="text" id='username' placeholder="Username">
            <input type="text" id='password' placeholder="Password">
            <button id="addProxy" onclick="addProxy();">Add Proxy</button>
        </div>
    </div>
    <div class="graph">
        <div id="bandwithGraph">
            <h4>Data usage</h4>
        </div>
</section>
<link rel="stylesheet" href="<?php $assets->getAssetsLink('/dashboard/pages/network/css/network.css'); ?>">
<script src="<?php $assets->getAssetsLink("/dashboard/js/network/addproxy.js"); ?>"></script>