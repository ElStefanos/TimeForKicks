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

use webhook\webhookDiscord;

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
    
    //Setting up for scrapping 

    $url = $value['url'];
    $baseURL = $url;
    $id = $value['id'];
    $startTime = microtime(true);

    //Setting up narrower

    $specialSearch = $dataBase->grabResultsTable('kicks_narrowing', "WHERE `url` = '$url';");

    if($specialSearch[0]['minimum_match'] == 0 || empty($specialSearch[0]['minimum_match'])) {
        $minMatch = 1;
    } else {
        $minMatch = $specialSearch[0]['minimum_match'];
    }

    $specialSearch = $specialSearch[0]['category'];

    echo "Searching: " . $value['url'] . "\n";

    echo "Narrowing: $specialSearch\n";

    //Fetching sitemap

    $robots = new robots($url, $id);

    $urlSitemap = $robots->getSiteMapURL();

    $siteMap = new sitemap($urlSitemap, $id);

    $siteMapFetched = $siteMap->crawlSiteMap($id);

    //Starting scrapper 

    foreach($siteMapFetched as $link) {
        if (narrowSearch($link, $specialSearch, $minMatch)) {
            $content = new webpageScraper($link, $id);

            $found = $content->scrapeAndSearch($baseURL);

            $found = array_map('array_filter', $found);
            $found = array_filter($found);

            if(!empty($found))
            {
                foreach($found as $product) {

                    $content = new webpageScraper($product["link"]);

                    $estimatedPrice = $content->getPrice();

                    $webhook = new webhookDiscord($product["link"], $estimatedPrice, $product["filter"], $baseURL);
                    $webhook->sendHook();
                    $webhook->sendHookSite($baseURL);

                }
            }

        }
    }

    $endTime = microtime(true);

    $time = $endTime - $startTime;
    $dataBase->updateTable('kicks_indexes', "`parse_time`='$time' WHERE `id` = '$id';");

}