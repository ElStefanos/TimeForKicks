<?php

namespace crawler;

use DOMDocument;
use network\network;
use filters\filterSearch;

class webpageScraper
{
    private string $url;
    public array $currencies;
    private $curl;

    public function __construct(string $url, string $id = '')
    {

        $this->url = $url;
        $this->curl = new network($this->url);

        if ($id != '') {
            $this->curl = new network($this->url, $id);
        }

        $this->currencies = file(__ROOT__.DIRECTORY_SEPARATOR."currencies.txt");

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

    public function webpageCrawler(string $baseURL) : array
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

    public function getPrice() : string
    {
        $html = $this->curl->curlTarget();

        $dom = new DOMDocument();
        $content = "N\A";

        $found = false;

        if (strlen(trim($html)) != 0) {
            @$dom->loadHTML($html);

            $tags = array('p', 'span', 'div', 'a');

            for($i = 0; $i <= 5; $i++) {
                $tags[] = 'h'.$i;
            }

            foreach($tags as $tag => $value) {

                foreach ($dom->getElementsByTagName($value) as $tag) {

                    if($tag->hasAttributes()) 
                    {   
                        foreach($tag->attributes as $attr) {
                            if(str_contains($attr->nodeValue, "price")) {
                                if(!str_contains($tag->textContent, "sale") && !str_contains($tag->textContent, "Sale")) {
                                    $content = $tag->textContent;
            
                                    $found = true;
            
                                    break;
                                }
                            }
                        }

                        if($found) break;

                    } else {

                        foreach($this->currencies as $currency) {
                            if(str_contains(strtolower($tag->textContent), strtolower($currency))) {

                                $content = $tag->textContent;

                                $found = true;
                                break;
                            }
                        }

                        if($found) break;

                    }
                    
                }

                if($found) break;
            }
            

        }

        $pattern = '/[0-9.,]+/';

        preg_match_all($pattern, $content, $matches);

        $price = implode('', $matches[0]);

        return $price;
    }

    public function scrapeAndSearch(string $baseURL) : array
    {   

        $html = $this->curl->curlTarget();

        $dom = new DOMDocument();

        $content = array(array());
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
                $test = $filterSearch->searchFilterHard($link, $filter);
                if ($test != false) {
                    $content[] = array('link' => $link, 'filter' => $test);
                }
            }
        }

        return $content;
    }
}
