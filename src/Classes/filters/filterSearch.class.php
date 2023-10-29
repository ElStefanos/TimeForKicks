<?php

namespace filters;

use filters\applyFilters;
use dataBase\dataBase;

class filterSearch
{

    protected array $filter;
    protected $url;
    protected string $filteredUrl;
    protected array $link;
    private object $mysqli;
    private array $dbResult;
    private int $indexId;
    protected array $file;
    protected string $search;


    public function __construct($url, $file = array())
    {
        $this->url = $url;
        $this->file = $file;
        $this->mysqli = new dataBase();
        $this->dbResult = $this->mysqli->grabResultsTable('kicks_indexes', "WHERE `url` = '$this->url'");
        foreach ($this->dbResult as $this->dbResult) {
            $this->indexId = $this->dbResult['id'];
        }
        $this->filter = $this->mysqli->grabResultsTable('kicks_filters', "WHERE `index_id` = '$this->indexId'  AND `status` = '1' OR `is_global` = '1' AND `status` = '1'");
        $this->link = array();
    }

    public function searchFilter()
    {
        foreach ($this->filter as $filtered) {
            $this->search = $filtered['filter'];
            $filter = new applyFilters($this->search);
            $this->search = $filter->createFilter();
            $sim = 17;
            foreach ($this->file as $key => $value) {
                $vFiltered = explode('/', $value);
                $vKey = count($vFiltered);
                $filter = new applyFilters($vFiltered[$vKey - 1]);
                $this->filteredUrl = $filter->createFilterURL();
                $similarity = similar_text($this->filteredUrl, $this->search);

                if ($similarity > $sim) {
                    $sim = $similarity;
                    array_push($this->link, $value);
                }

                if (strpos($this->filteredUrl, $this->search) !== false) {
                    array_push($this->link, $value);
                }
            }
        }
        $sent = $this->mysqli->grabResultsTable('kicks_found_indexes');
        foreach ($sent as $key) {
            if (in_array($key['url'], $this->link)) {
                $index = array_search($key['url'], $this->link);
                unset($this->link[$index]);
            }
        }

        $this->link = array_unique($this->link);
        $this->link = array_values($this->link);

        foreach ($this->filter as $filtered) {

            foreach ($this->link as $link) {

                $id = $filtered['id'];
                $this->mysqli->insertTable('kicks_found_indexes', '(`filter_id`, `url`, `status`)', "('$id', '$link', '1')");
            }
        }

        return $this->link;
    }


    public function searchFilterHard($url, $content)
    {
        $sent = $this->mysqli->grabResultsTable('kicks_found_indexes');

        foreach ($this->filter as $filtered) {
            $this->search = $filtered['filter'];
            $filter = new applyFilters($this->search);
            $this->search = $filter->createFilter();

            foreach ($content as $word) {
                $filter = new applyFilters($word);
                $filteredContent = $filter->createFilterURL();
                    
                if (strpos($filteredContent, $this->search) !== false) {

                    echo "=============================================\n";
                    echo "                 NEW PRODUCT                 \n";
                    echo "Found product with filter: " . $this->search . "\n";
                    echo "URL: " . $url . "\n";

                    foreach ($sent as $key) {
                        if (in_array($key['url'], array($url))) {

                            echo "Already found: TRUE \n";
                            echo "=============================================\n";

                            return false;
                        }
                    }

                    echo "Already found: FALSE \n";
                    echo "=============================================\n";

                    $id = $filtered['id'];
                    $this->mysqli->insertTable('kicks_found_indexes', '(`filter_id`, `url`, `status`)', "('$id', '$url', '1')");
                    return true;
                }
            }
        }
    }
}
