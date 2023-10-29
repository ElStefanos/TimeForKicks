<?php

use caching\loadCache;
use crawler\robots;
use crawler\sitemap;

function cacheControl($sites, $maxAge)
{


    foreach ($sites as $site) {

        if ($site['type'] == 'easy' || $site['type'] == 'medium') {
            $robots = new robots($site['url']);
            $site['url'] = $robots->getSiteMapURL();
        }

        $checkCache = new loadCache($site['url']);

        if ($checkCache->checkCache()) {

            $creation = $checkCache->getCreationTime();

            $current = time();

            echo $site['url'] . ": cache creation time is $creation\n";

            if ($creation + $maxAge < $current) {

                echo "Cache age $creation. Current time: $current. Age difference > $maxAge\n";

                echo $site['url'] . ": cache is too old deleting.....\n";

                if ($checkCache->deleteCache()) {

                    echo "Cache deleted for: " . $site['url'] . "\n";
                } else {

                    echo "Failed to delete cache for: " . $site['url'] . "\n";
                }
            } else {

                echo $site['url'] . ": cache is legit.....\n";
            }
        } else {
            echo $site['url'] . ": has no cache\n";
        }
    }
}
