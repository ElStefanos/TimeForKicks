<?php

namespace identify;

use filters\applyFilters;
use crawler\robots;
use crawler\sitemap;

class identifySite
{
    private $filter;
    private string $filteredURL;
    private array $links;
    private $robots;
    private $sitemap;

    private string $url;

    public function __construct($site)
    {
        $this->url = $site;
        $this->robots = new robots($this->url);
        $this->filter = new applyFilters($this->url);
        $this->filteredURL = $this->filter->createFilterURL();
    }

    private function is_valid_domain($domain)
    {
        if(filter_var(gethostbyname($domain), FILTER_VALIDATE_IP))
        {
            return TRUE;
        }

        return FALSE;
    }

    public function identify()
    {

        if (!$this->is_valid_domain($this->url)) {
            return 3;
        }

        if (str_contains($this->filteredURL, "buzzsneakers")) {
            return 1;
        }
        
        if ($this->robots->checkSiteMap() == 0) {
            return 0;
        }

        
        $this->sitemap = new sitemap($this->robots->getSiteMapURL(), $this->url);

        if($this->sitemap->crawlSiteMap() != 0) {
            $this->links = $this->sitemap->crawlSiteMap();
        } else {
            return 0;
        }


        foreach ($this->links as $link) {
            $this->filter = new applyFilters($link);
            $newLink = $this->filter->createFilterURL();
            if (str_contains($newLink, 'cdn shopify')) {
                return 1;
            }
        }

        return 2;

        //0 - hard, 1 - easy, 2 - medium, 3 - not compatible

    }
}
