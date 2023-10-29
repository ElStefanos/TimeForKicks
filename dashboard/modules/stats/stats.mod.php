<?php

use dashboard\{getAssets, getModules, getUserInfo, requestedPage};

$modules = new getModules;
$page = new requestedPage;

use admin\adminCheck;
use database\dataBase;

$admin = new adminCheck;

$admin->checkAdmin();
$page = $page->returnPage();
$assets = new getAssets;
$userInfo = new getUserInfo;
$userInfo = $userInfo->getInfo();
$dataBase = new dataBase;

$resultAll = $dataBase->grabResultsTable('kicks_indexes');
$numbSites = count($resultAll);
$resultAllFound = $dataBase->grabResultsTable('kicks_found_indexes');

$totalFound = count($resultAllFound);
$total_links = 0;
$siteTime = array();

$filters = $dataBase->grabResultsTable('kicks_filters');

$filtersFound = array();
foreach ($filters as $key => $value) {
    $filterId = $value['id'];

    $index = $dataBase->grabResultsTable('kicks_found_indexes', "WHERE `filter_id` = '$filterId';");

    $filtersFound[][$value['filter']] = 0;

    if (is_array($index)) {
        $filtersFound[][$value['filter']] = count($index);
    }
}

foreach ($resultAll as $result) {
    $total_links = $total_links + $result['number_of_links'];

    if ($result['parse_time'] == NULL) {
        $result['parse_time'] = 0;
    }

    $siteTime[$result['url']] = $result['parse_time'];
}


?>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Responsive.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script>
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("lastTimeStats");


        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        root.setThemes([
            am5themes_Responsive.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));

        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        xRenderer.grid.template.setAll({
            location: 1
        })

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "site",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: am5xy.AxisRendererY.new(root, {
                strokeOpacity: 0.1
            })
        }));


        // Create series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "site",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5,
            strokeOpacity: 0
        });
        series.columns.template.adapters.add("fill", function(fill, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });


        // Set data
        var data = [
            <?php
            $total = 0;
            foreach ($siteTime as $key => $value) {
                echo '{ site: "' . $key . '", value: ' . $value . ' },';
                $total = $total + $value;
            }
            echo '{ site: "Total: ", value: ' . $total . ' },';
            ?>
        ];

        xAxis.data.setAll(data);
        series.data.setAll(data);

        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("filterProductsStats");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            root.setThemes([
                am5themes_Responsive.new(root)
            ]);

            // Create chart
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout
            }));


            // Create series
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category"
            }));


            // Set data
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
            series.data.setAll([
                <?php
                $total = 0;
                foreach ($filtersFound as $term) {
                    foreach ($term as $key => $value) {
                        if ($value > 0) {
                            echo '{ category: "' . $key . '", value: ' . $value . ' },';
                            $total = $total + $value;
                        }
                    }
                }
                ?>
            ]);


            // Play initial series animation
            // https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
            series.appear(1000, 100);

        }); // end am5.ready()

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(1000);
        chart.appear(1000, 100);

    }); // end am5.ready()
</script>
<link rel="stylesheet" href="<?php $assets->getAssetsLink("/dashboard/modules/stats/css/stats.css"); ?>">
<div class="statistics">
    <div class="stats-overview">
        <div class="total-container">
            <div class="total-links">
                <span>
                    <h4>Number of parsed links</h4>
                    <p><?php echo $total_links; ?></p>
                </span>
            </div>
            <div class="total-links">
                <span>
                    <h4>Number of found products</h4>
                    <p><?php echo $totalFound; ?></p>
                </span>
            </div>
            <div class="total-links">
                <span>
                    <h4>Number of searched stores</h4>
                    <p><?php echo $numbSites; ?></p>
                </span>
            </div>
        </div>
        <div class="charts">
            <div class="charts-container">
                <h3>Time to search</h3>
                <div id="lastTimeStats">
                    </div>
                </div>
                <div class="charts-container">
                <h3>Most frequent products</h3>
                <div id="filterProductsStats">
                </div>
            </div>
        </div>
    </div>
    <div class="other-container">
        <div class="sites-overview">
            <?php
            foreach ($resultAll as $key => $value) {
                $name = $value['url'];
                echo "
                        <a href='http://$name'>
                        <div class='sites-item'>
                        <h3>$name</h3>
                        </div>
                        </a>
                    ";
            }
            ?>
        </div>
    </div>
</div>
<script src="<?php $assets->getAssetsLink("/dashboard/js/stats/fetch-notifications.js"); ?>"></script>