<?php

namespace events;

use dataBase\dataBase;
use filters\{applyFilters, filterSearch};
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class check
{
    private string $site;
    private array $file;
    private int $fileLines;
    private int $newLinks;
    private array $links;
    private object $mysqli;

    public function __construct($file, $links, $site)
    {
        $this->links = $links;
        $this->file = $file;
        $this->site = $site;
    }

    public function compareLines()
    {
        $this->fileLines = count($this->file);

        $this->newLinks = count($this->links);

        $this->mysqli = new dataBase;

        if ($this->newLinks == 0) {
            $this->mysqli->updateTable('kicks_indexes', "`number_of_links`='$this->newLinks' WHERE `url` = '$this->site'");
            return true;
        }

        if ($this->fileLines > $this->newLinks) {
            $this->mysqli->updateTable('kicks_indexes', "`number_of_links`='$this->newLinks' WHERE `url` = '$this->site'");
            return true;
        }

        if ($this->fileLines < $this->newLinks) {
            $this->mysqli->updateTable('kicks_indexes', "`number_of_links`='$this->newLinks' WHERE `url` = '$this->site'");
            return true;
        }

        return false;
    }
}
