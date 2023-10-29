<?php
use caching\loadCache;
use caching\createCache;
use crawler\sitemap;

$loadCache = new loadCache($urlSitemap);

if($loadCache->checkCache()) {

    $cached = $loadCache->loadCache();



} else {



}


