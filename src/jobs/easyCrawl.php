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


include __FUNCTIONS__ . 'autoloader.php';

use dataBase\dataBase;

use crawler\{robots, sitemap, webpageScraper};

use caching\{loadCache, createCache};

use filters\{applyFilters, filterSearch};

use webhook\webhookDiscord;

use events\{notifications, check};

$dataBase = new dataBase;

$results = $dataBase->grabResultsTable('kicks_indexes', "WHERE `type` = 'easy' AND `active` = '1';");

foreach ($results as $key => $value) {

    $startTime = microtime(true);
    $id = $value['id'];
    $baseUrl = $value['url'];

    echo "Searching: " . $value['url'] . "\n";

    $robotsCrawler = new robots($value['url'], $id);

    $check = $robotsCrawler->checkSiteMap();

    if($check < 200 || $check > 299) {
        echo "Code: ". $check;
        echo "\nNetwork error... Skipping....\n";
        goto a;
    }

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

        $diff = array(array_diff(array_map('trim', $siteMapFetched), array_map('trim', $cached)));

        echo "Cache diff is " . count($diff) . " for: " . $value['url'] . "\n";

        if (count($diff) > 0) {
            
            $writeCache = new createCache($urlSitemap);

            foreach ($diff as $word) {

                $searchFilter = new filterSearch($value['url'], $word);

                $found = $searchFilter->searchFilter();

                foreach($found as $link) {
                    $scrapper = new webpageScraper($link);

                    $priceGuess = $scrapper->getPrice();

                    $priceGuess = str_replace(" ", "", $priceGuess);

                    $webHook = new webhookDiscord($link, $priceGuess);

                    $webHook->sendHook();
                    $webHook->sendHookSite($baseUrl);
                }

                $writeCache->writeCache($found);

                echo "Adding to cache " . $value['url'] . "\n";
            }


        } else {
            echo "Skipping search for: " . $value['url'] . "\n";
        }
    } else {
        echo "Cache not found for: " . $value['url'] . ". Writting cache....\n";

        $writeCache = new createCache($urlSitemap);

        $writeCache->writeCache($siteMapFetched);

        $searchFilter = new filterSearch($value['url'], $siteMapFetched);

        $found = $searchFilter->searchFilter();

        

        foreach($found as $link) {
            $scrapper = new webpageScraper($link);
            $priceGuess = $scrapper->getPrice();
            if(empty($priceGuess)) $priceGuess = "N/A";
            $webHook = new webhookDiscord($link, $priceGuess, "New product!", $baseUrl);
            $webHook->sendHook();
            $webHook->sendHookSite($baseUrl);
        }

        if (is_array($found)) {
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
