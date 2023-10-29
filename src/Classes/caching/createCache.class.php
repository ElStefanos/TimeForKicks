<?php

namespace caching;

class createCache {
    protected $file;
    protected string $cache;
    protected string $dir;
    protected string $fullPath;
    protected string $hash;
    protected $date;
    protected $time;

    public function __construct($file) {
        $this->file = $file;
        $this->hash = hash('SHA512', $file);
        $this->hash = substr($this->hash, 0, 10);
        $this->dir = __CACHE__;
        $this->fullPath = $this->dir.$this->hash.DIRECTORY_SEPARATOR.$this->hash;
        if (!file_exists($this->fullPath)) {
            mkdir($this->dir.$this->hash, 0777);
            $this->file = fopen($this->fullPath, 'w');
            fclose($this->file);
            chmod($this->fullPath, 0777);
        }
    }

    public function writeCache($write) {
        $this->file = fopen($this->fullPath, 'a');

        if(is_array($write)) {
            foreach ($write as $key) {
                fwrite($this->file, PHP_EOL.$key.PHP_EOL);
            }
        } else {
            fwrite($this->file, PHP_EOL.$write);
        }


        fclose($this->file);

    }

}