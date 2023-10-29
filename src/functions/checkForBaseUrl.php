<?php


use filters\applyFilters;

function checkForBase($link, $base)
{
    $link = trim($link);

    if(str_starts_with($link, "/")) {
        $link = "http://".$base.$link;
    }

    $filteredUrl = new applyFilters($link);

    $link = $filteredUrl->createFilterURL();

    $filteredUrl = new applyFilters($base);

    $base = $filteredUrl->createFilterURL();

    $link = trim($link);

    echo $base."\n$link\n";

    $blacklist = array('#', 'https://twitter.com', 'https://web.whatsapp.com', 'tel:', 'mailto:', 'https://www.wspay.rs', 'https://rs.visa.com', 'mastercard', 'javascript:', 'https://www.facebook.com', 'banka', 'bank', 'banca', 'https://www.pinterest.com', 'americanexpress', 'americanexpress', 'https://www.instagram.com', 'https://www.bancaintesa.rs', 'raiffeisen', '');

    if (str_starts_with($link, $base)) {
        return true;
    }

    return false;
}
