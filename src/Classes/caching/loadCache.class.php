<?php

namespace caching;


use DateTime;

class loadCache {
    
    protected string $dir;
    protected $date;
    protected $time;
    protected string $file;
    protected string $fullPath;
    protected string $hash;

    public function __construct($file) {
        $this->file = 'temp';
        if($file !== null) {

            $this->file = $file;
        }
        $this->date = new DateTime('now');
        $this->time = strtotime('now');
        $this->hash = hash('SHA512', $file);
        $this->hash = substr($this->hash, 0, 10);
        $this->dir = __CACHE__;
        $this->fullPath = $this->dir.$this->hash.DIRECTORY_SEPARATOR.$this->hash;
    }

    public function checkCache() {
        if (file_exists($this->fullPath)) {
            $created = filectime($this->dir.$this->hash);
            $ago = $this->time - $created;
            return true;
        } else {
            return false;
        }
    }

    public function loadCache() {
        if($this->checkCache()) {
            return $file = file($this->fullPath);
        } else {
            return array();
        }
    }

    public function getCreationTime()
    {
        return filectime($this->fullPath);
    }

    public function deleteCache() {
        if(unlink($this->fullPath) && rmdir($this->dir.$this->hash)) {
            return true;
        }
    }

}