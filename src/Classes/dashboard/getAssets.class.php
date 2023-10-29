<?php

namespace dashboard;

class getAssets
{
    public function getAssetsLink($assets) {
        $assets = __URL__.$assets;
        echo $assets;
    }

    public function getPageLink($page) {
        $assets = __URL__.'/dashboard?page='.$page;
        echo $assets;
    }

}