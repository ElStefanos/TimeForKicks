<?php

namespace crawler;

use DOMDocument;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use network\network;
use shopify\checkShopify;
use filters\applyFilters;

class sitemap
{

    protected string $url;
    protected string $baseUrl;
    protected $paths;
    protected $doc;
    protected $links;
    protected $headers;
    protected string $link;

    public function __construct($url, $baseUrl)
    {
        $this->url = $url;
        $this->baseUrl = $baseUrl;
        $this->doc = new DOMdocument();
        $this->url = $url;
    }


    public function crawlSiteMap($id = '')
    {
        $network = new network($this->url);

        if($id != '') {
            $network = new network($this->url, $id);
        }
        $result = $network->curlTarget();

        $this->links = array();

        if (trim(strlen($result)) > 0) {
            @$this->doc->loadHTML($result);
            foreach ($this->doc->getElementsByTagName('loc') as $loc) {
                $this->links[] = $loc->textContent;
            }
        } else {
            return 0;
        }


        $this->paths = array();

        $this->links = array_values($this->links);

        foreach ($this->links as $this->link => $value) {
            $value = preg_replace("/\r|\n/", "", $value);
            $value = urldecode($value);
            if (str_contains($value, '.xml') || str_contains($value, 'sitemap') || str_contains($value, 'sitemaps')) {
                $crawler = new sitemap($value, $this->baseUrl);
                $array = $crawler->crawlSiteMap($id);
                array_push($this->paths, $array);
            } else {
                array_push($this->paths, $value);
            }
        }

        $this->paths = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($this->paths)), 0);
        $this->paths = array_unique($this->paths);

        foreach ($this->paths as $key => $value) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                unset($this->paths[$key]);
            }
        }

        $this->paths = array_values($this->paths);
        return $this->paths;
    }
}
