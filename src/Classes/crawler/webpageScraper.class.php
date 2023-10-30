<?php

namespace crawler;

use DOMDocument;
use network\network;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

use filters\applyFilters;
use filters\filterSearch;

class webpageScraper
{
    private string $url;
    private array $headers;

    private $curl;

    public function __construct($url, $id = '')
    {

        $this->url = $url;
        $this->curl = new network($this->url);

        if ($id != '') {
            $this->curl = new network($this->url, $id);
        }
    }


    public function webpageScraper()
    {

        $html = $this->curl->curlTarget();

        $dom = new DOMDocument();

        if (strlen(trim($html)) != 0) {
            @$dom->loadHTML($html);

            $content = array();

            foreach ($dom->getElementsByTagName('p') as $p) {
                $content[] = $p->textContent;
            }

            foreach ($dom->getElementsByTagName('a') as $p) {
                $content[] = $p->textContent;
            }

            for ($i = 1; $i < 5; $i++) {
                foreach ($dom->getElementsByTagName('h' . $i) as $p) {
                    $content[] = $p->textContent;
                }
            }
        }


        return $content;
    }

    public function webpageCrawler($baseURL)
    {
        $html = $this->curl->curlTarget();
        $dom = new DOMDocument();
        $content = array();

        if (strlen(trim($html)) != 0) {
            @$dom->loadHTML("<html>" . $html . "</html>");
            foreach ($dom->getElementsByTagName('a') as $a) {
                if (str_starts_with($a->getAttribute('href'), "/")) {
                    $link = "http://" . $baseURL . $a->getAttribute('href');
                } else {
                    $link = $a->getAttribute('href');
                }
                $content[] = $link;
            }
        }

        return $content;
    }

    public function getPrice()
    {
        $html = $this->curl->curlTarget();

        $dom = new DOMDocument();
        $content = array();
        if (strlen(trim($html)) != 0) {
            @$dom->loadHTML($html);

            $tags = array('p', 'span', 'div');

            for($i = 0; $i <= 5; $i++) {
                $tags[] = 'h'.$i;
            }

            foreach($tags as $tag => $value) {
                foreach ($dom->getElementsByTagName($value) as $tag) {
    
                    if(str_contains($tag->getAttribute('class'), 'price')) {
                        return $tag->textContent;
                    }
    
                }
            }
        }
        return $content;
    }

    public function scrapeAndSearch($baseURL, $link)
    {
        $html = $this->curl->curlTarget();

        $dom = new DOMDocument();
        $content = array();
        if (strlen(trim($html)) != 0) {
            @$dom->loadHTML($html);


            foreach ($dom->getElementsByTagName('a') as $a) {

                if (str_starts_with($a->getAttribute('href'), "/")) {
                    $link = "http://" . $baseURL . $a->getAttribute('href');
                } else {
                    $link = $a->getAttribute('href');
                }

                $filter = array($a->textContent);
                $filterSearch = new filterSearch($baseURL, $filter);
                if ($filterSearch->searchFilterHard($link, $filter)) {
                    $content[] = $link;
                }
            }
        }

        return $content;
    }
}
