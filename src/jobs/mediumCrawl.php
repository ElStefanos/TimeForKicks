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

include __FUNCTIONS__ . 'autoloader.php';

use dataBase\dataBase;

use crawler\{robots, sitemap, webpageScraper};

use caching\{loadCache, createCache};

use filters\{applyFilters, filterSearch};

use webhook\webhookDiscord;

use events\{notifications, check};

$dataBase = new dataBase;

$results = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'medium' AND `active` = '1';");

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
    $baseURL = $url;

    $id = $value['id'];
    $startTime = microtime(true);

    $specialSearch = $dataBase->grabResultsTable('kicks_narrowing', "WHERE `url` = '$url';");

    if (!is_array($specialSearch)) {
        echo "ERORR\n\n";
        goto a;
    }

    if($specialSearch[0]['minimum_match'] == 0 || empty($specialSearch[0]['minimum_match'])) {
        $minMatch = 1;
    } else {
        $minMatch = $specialSearch[0]['minimum_match'];
    }
    $specialSearch = $specialSearch[0]['category'];

    echo "Searching: " . $value['url'] . "\n";

    echo "Narrowing: $specialSearch\n";



    $robotsCrawler = new robots($value['url'], $id);

    $urlSitemap = $robotsCrawler->getSiteMapURL();

    $loadCache = new loadCache($urlSitemap);

    $siteMap = new sitemap($urlSitemap, $value['url']);

    $siteMapFetched = $siteMap->crawlSiteMap($id);

    $updateLinks = new check(array(), $siteMapFetched, $value['url']);

    $updateLinks->compareLines();

    if ($loadCache->checkCache()) {

        echo "Cache found for: " . $value['url'] . "\n";

        $cached = $loadCache->loadCache();

        $cached = array_filter($cached);

        $diff = array_diff(array_map('trim', $siteMapFetched), array_map('trim', $cached));

        echo "Cache diff is " . count($diff) . " for: " . $value['url'] . "\n";

        if (count($diff) > 0) {
            
            $found = array();
            foreach ($diff as $link) {
                $writeCache = new createCache($urlSitemap);
                $writeCache->writeCache($link);
                if (narrowSearch($link, $specialSearch, $minMatch)) {
                    $content = new webpageScraper($link, $id);

                    $scrapped = $content->webpageScraper();

                    $searchFilter = new filterSearch($value['url']);


                    if ($searchFilter->searchFilterHard($link, $scrapped)) {
                        $found[] = $link;
                    }
                }
            }

            if (count($found) > 0) {
                $webHook = new webhookDiscord($found);

                $webHook->sendHook();
                $webHook->sendHookSite($baseURL);
                print_r($found);
            }
        } else {
            echo "Skipping search for: " . $value['url'] . "\n";
        }
    } else {

        echo "Cache not found for: " . $value['url'] . ". Writting cache....\n";

        $writeCache = new createCache($urlSitemap);

        $writeCache->writeCache($siteMapFetched);

        $found = array();

        echo "Searching: " . $value['url'] . "\n";

        foreach ($siteMapFetched as $link) {
            if (narrowSearch($link, $specialSearch, $minMatch)) {
                $content = new webpageScraper($link, $id);

                $scrapped = $content->webpageScraper();

                $searchFilter = new filterSearch($value['url']);

                if ($searchFilter->searchFilterHard($link, $scrapped)) {

                    $found[] = $link;
                }
            }
        }

        if (count($found) > 0) {
            $webHook = new webhookDiscord($found);

            $webHook->sendHook();
            $webHook->sendHookSite($baseURL);

            print_r($found);
            $notify = new notifications;
            foreach ($found as $item) {
                echo $item;
            }
        }
    }

    $endTime = microtime(true);

    $time = $endTime - $startTime;
    $dataBase->updateTable('kicks_indexes', "`parse_time`='$time' WHERE `id` = '$id';");
    a:
}

echo "\n\nJob finished on:  \n";
echo date('l jS \of F Y h:i:s A');
echo "\n\n++++++++++++++++++++++++++++++++++\n";
