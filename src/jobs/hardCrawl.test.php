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

use events\notifications;

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
        continue;
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

    $cacheOk = 0;

    if ($loadCache->checkCache() == false) {
        echo "\n\nCache not found for: $baseUrl\n";
        echo "\n\nWriting cache for: $baseUrl\n";
        $writeCache = new createCache($baseUrl);

        $siteMap = new webpageScraper($url, $siteId);

        $siteMapFetched = $siteMap->webpageCrawler($baseUrl);

        $visited[] = $url;
        $visited[] = $baseUrl;

        $siteMapFetched = array_unique($siteMapFetched);


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


        $writeCache->writeCache($siteMapFetched);

        echo "\n\nCache for: $baseUrl was created and will be used as a sitemap for next 2 hours.\n";

        $cacheOk = 1;
    } else {
        $cacheOk = 1;
    }

    if ($cacheOk == 1) {

        echo "\n\n\nStarting product search for: $baseUrl\n\n";


        $loadCache = new loadCache($baseUrl);

        $siteMap = $loadCache->loadCache();
        $siteMap = array_map('trim', $siteMap);
        $siteMapFetched = array_values(array_filter($siteMap));

        foreach ($siteMapFetched as $link) {
            if (narrowSearch($link, $specialSearch, $minMatch)) {

                $content = new webpageScraper($link, $siteId);

                $found = $content->scrapeAndSearch($value['url']);


                $found = array_map('array_filter', $found);
                $found = array_filter($found);

                if (!empty($found)) {
                    foreach ($found as $product) {

                        $content = new webpageScraper($product["link"]);

                        $estimatedPrice = $content->getPrice();

                        $webhook = new webhookDiscord($product["link"], $estimatedPrice, $product["filter"], $baseUrl);
                        $webhook->sendHook();
                        $webhook->sendHookSite($baseURL);

                    }
                }

            }
        }


    } else {
        echo "\n\nERROR for site $baseUrl\n Continuing.. \n\n";
        continue;
    }

    $endTime = microtime(true);

    $time = $endTime - $startTime;

    $dataBase->updateTable('kicks_indexes', "`parse_time`='$time' WHERE `id` = '$siteId';");

}