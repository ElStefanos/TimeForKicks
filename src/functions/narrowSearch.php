<?php

use filters\{applyFilters, filterSearch};

function narrowSearch($link, $terms, $level)
{


    $terms_cp = $terms;
    $terms = explode(' ', $terms);
    $matchNumber = 0;

    $filterURL = new applyFilters($link);
    $filterURL = $filterURL->createFilterURL();

    $elementsURL = explode(' ', $filterURL);

    foreach ($elementsURL as $elements) {
        if (in_array($elements, $terms)) {
            $matchNumber++;
            if ($matchNumber == $level) {
                echo "Match for specialized search:\n";
                echo "Match level $matchNumber\n";

                echo $terms_cp . " ===========> " . $link . "\n";
                return true;
            }
        }
    }

    return false;
}
