<?php

include '../../directoryMap.php';
include __FUNCTIONS__ . 'autoloader.php';

use admin\adminCheck;
use filters\filterSearch;
use webhook\webhookDiscord;
use caching\loadCache;
use caching\createCache;
use crawler\sitemap;
use crawler\robots;

$admin = new adminCheck;

$admin->checkAdmin();

if (isset($_POST['search-filter'])) {
    $url = $_POST['site'];
    $urlBase = "https://".$url."/";
    $crawler = new robots($urlBase);
    $urlSitemap = $crawler->getSiteMapURL();
    $sitemap = new sitemap($urlSitemap, $url);
    $links = $sitemap->crawlSiteMap($urlSitemap);
    $search = new filterSearch($url, $links);
    $found = $search->searchFilter();
    $hook = new webhookDiscord($found);
    $hook->sendHook();
} else {
    header("Location:".__URL__."/dashboard?page=dashboard&error_search_not_set");
    exit();
}
header("Location:".__URL__."/dashboard?page=dashboard&searched");
exit();
?>