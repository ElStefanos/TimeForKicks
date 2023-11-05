<?php

namespace crawler;
use network\network;


class robots
{
    protected $rules;
    protected $url;
    protected $sitemap;
    protected $code;
    private $id;

    public function __construct(string $url, string $id='')
    {   

        $this->url = $url.'/robots.txt';

        $curl = new network($this->url, $id);

        if($id != '') {
            $curl = new network($this->url, $id);
            $this->id = $id;
        }

        $this->rules = $curl->curlTarget();
        $this->rules = explode("\n",  $this->rules);
        $this->rules = preg_grep('/[^\s]/',  $this->rules);
    }
    
    public function checkSiteMap() : int
    {
        foreach ($this->rules as $key => $value) {
            if (strpos($value, 'Sitemap:') !== false || strpos($value, 'sitemap:') !== false) {
                $curl = curl_init();
                $value = explode('https://', $value);
                $value = trim($value[1]);
                $curl = new network($value, $this->id);
                $curl->curlTarget();
                $this->code = $curl->getCode();
                if($this->code > 0) {
                    return (int) $this->code;
                }
            }
        }
        return 0;
    }

    public function getSiteMapURL() : string
    {
        foreach ($this->rules as $key => $value) {
            if (strpos($value, 'Sitemap:') !== false || strpos($value, 'sitemap:') !== false) {
                $this->sitemap = explode(' ', $value);
                if (isset($this->sitemap[1])) {
                    $this->sitemap = str_replace(' ', '', $this->sitemap[1]);
                } else {
                    $this->sitemap = str_replace(' ', '', $this->sitemap[0]);
                }
                return $this->sitemap;
            }
        }
    }
}
