<?php

echo "\n\n++++++++++++++++++++++++++++++++++\n";
echo date('l jS \of F Y h:i:s A');
echo "\n\nJob started...\n";

echo __DIR__ . "\n";

if (file_exists('./directoryMap.php')) {
    include './directoryMap.php';
} else {
    include '../../directoryMap.php';
}

include __FUNCTIONS__ . 'narrowSearch.php';
include __FUNCTIONS__ . 'checkForBaseUrl.php';
include __FUNCTIONS__ . 'autoloader.php';

use dataBase\dataBase;

use crawler\webpageScraper;


use caching\loadCache;
use caching\createCache;

use webhook\webhookDiscord;

use events\{notifications, check};

$dataBase = new dataBase;

$results = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'hard' AND `active` = '1';");

foreach ($results as $key => $value) {
    $id = $value['id'];
    $filters = $dataBase->grabResultsTable('kicks_filters', "WHERE `index_id` = '$id' OR `is_global` = '1';");
    if (is_array($filters) && count($filters) > 0) {
        var_dump($filters);
    } else {
        echo "URL: " . $value['url'] . " has no filters, skipping.\n";
        unset($results[$key]);
    }
}


echo "List of sites: \n\n";
print_r($results);
echo "\n\n";

foreach ($results as $key => $value) {

    $url = $value['url'];
    $baseUrl = $value['url'];
    $siteId = $value['id'];

    if (!empty($value['start_url'])) {
        $url = $url . '/' . trim($value['start_url']) . '/';
    }

    $startTime = microtime(true);

    $loadCache = new loadCache($url);

    $specialSearch = $dataBase->grabResultsTable('kicks_narrowing', "WHERE `url` = '$baseUrl';");

    if (!is_array($specialSearch)) {
        echo "ERORR\n\n";
        goto a;
    }

    var_dump($specialSearch);

    if ($specialSearch[0]['minimum_match'] == 0 || empty($specialSearch[0]['minimum_match'])) {
        $minMatch = 1;
    } else {
        $minMatch = $specialSearch[0]['minimum_match'];
    }

    $specialSearch = $specialSearch[0]['category'];

    echo "Searching: " . $url . "\n";

    echo "Narrowing: $specialSearch\n Min match $minMatch\n";

    $siteMap = new webpageScraper($url, $siteId);

    $siteMapFetched = $siteMap->webpageCrawler($baseUrl);

    $siteMapFetched = array_unique($siteMapFetched);
    $visited[] = $url;

    $updateLinks = new check(array(), $siteMapFetched, $baseUrl);

    $updateLinks->compareLines();

    foreach ($siteMapFetched as $url) {
        if (str_starts_with($url, "/")) {
            $url = "http://" . $baseUrl . $url;
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            if (!in_array($url, $visited)) {


                $siteMap = new webpageScraper($url, $siteId);

                $newlyFetched = $siteMap->webpageCrawler($baseUrl);

                $visited[] = $url;

                foreach ($newlyFetched as $url) {
                    if (str_starts_with($url, "/")) {
                        $url = "http://" . $baseUrl . $url;
                    }
                    if (!in_array($url, $siteMapFetched) && !in_array($url, $visited) && narrowSearch($url, $specialSearch, $minMatch) && checkForBase($url, $baseUrl)) {
                        echo "New URL found: $url \n";

                        if (str_starts_with($url, "/")) {
                            $url = "http://" . $baseUrl . $url;
                        }

                        $siteMapFetched[] = $url;
                    }
                }
            }
        }
    }

    if ($loadCache->checkCache() == false) {
        echo "\n\nCache not found for: $baseUrl\n";
        echo "\n\nWriting cache for: $baseUrl\n";
        $writeCache = new createCache($baseUrl);
        $writeCache->writeCache($siteMapFetched);
        $cacheOk = 0;
    } else {
        echo "\n\nCache was found for: $baseUrl\n";
        $cached = $loadCache->loadCache();
        echo "\n\nCache loaded for: $baseUrl\n";
        $cacheOk = 1;
    }

    if ($cacheOk == 1) {
        $cached = $loadCache->loadCache();

        $cached = array_filter($cached);

        $diff = array_diff(array_map('trim', $siteMapFetched), array_map('trim', $cached));

        echo "Cache diff is " . count($diff) . " for: " . $value['url'] . "\n";

        $siteMapFetched = $diff;
        if (!is_array($siteMapFetched)) {
            $siteMapFetched = array();
        }

        if (count($siteMapFetched) > 0) {
            echo "Writing cache diff....\n";
            $writeCache = new createCache($baseUrl);
            $writeCache->writeCache($siteMapFetched);
        }
    }

    $siteMapFetched = array_unique($siteMapFetched);
    $siteMapFetched = array_filter($siteMapFetched);
    $siteMapFetched = array_values($siteMapFetched);

    var_dump($siteMapFetched);
    echo "\n\nMap Building finished on:  \n";
    echo date('l jS \of F Y h:i:s A');
    sleep(5);
    echo "\n==================================\n";
    echo "Starting product search:  \n";
    echo date('l jS \of F Y h:i:s A'), "\n";

    $found = array();

    foreach ($siteMapFetched as $link) {
        if (narrowSearch($link, $specialSearch, $minMatch)) {
            $content = new webpageScraper($link);

            $found[] = $content->scrapeAndSearch($value['url'], $link);
        }
    }

    var_dump($found);

    if (count($found) > 0) {
        $found = array_map('array_filter', $found);
        $found = array_filter($found);
        $found = array_values($found);


        foreach ($found as $key => $value) {
                foreach($value as $link) {
                    $priceSearch = new webpageScraper($link);
                    $priceSearch = $priceSearch->getPrice();
        
                    $webHook = new webhookDiscord($link, $priceSearch);
        
                    $webHook->sendHook();
                    $webHook->sendHookSite($baseUrl);
                }
        }

        print_r($found);
        $notify = new notifications;
        foreach ($found as $item) {
            echo $item;
        }
    }

    $endTime = microtime(true);
    a:
    $time = $endTime - $startTime;

    $dataBase->updateTable('kicks_indexes', "`parse_time`='$time' WHERE `id` = '$siteId';");
    $dataBase->updateTable('kicks_indexes', "`parse_time`='$time' WHERE `url` = '$baseUrl';");
}

echo "\n\nJob finished on:  \n";
echo date('l jS \of F Y h:i:s A');
echo "\n\n++++++++++++++++++++++++++++++++++\n";
