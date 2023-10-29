<?php

namespace filters;

class applyFilters
{

    protected string $word;
    protected string $filtered;

    public function __construct($word)
    {
        $this->word = $word;
    }

    public function createFilter()
    {

        $this->filtered = strtolower($this->word);
        $this->filtered = str_replace("'", '', $this->filtered);
        $this->filtered = str_replace("&", '', $this->filtered);
        $this->filtered = str_replace("-", ' ', $this->filtered);
        $this->filtered = str_replace("/", '', $this->filtered);
        $this->filtered = str_replace("=", '', $this->filtered);
        $this->filtered = str_replace(",", ' ', $this->filtered);
        $this->filtered = str_replace('<', ' ', $this->filtered);
        $this->filtered = str_replace('>', ' ', $this->filtered);
        $this->filtered = preg_replace('!\s+!', ' ', $this->filtered);
        $this->filtered = strtolower($this->filtered);

        return $this->filtered;
    }

    public function createFilterURL()
    {
        $this->filtered = strtolower($this->word);
        $this->filtered = str_replace('https:', ' ', $this->filtered);
        $this->filtered = str_replace('http:', ' ', $this->filtered);
        $this->filtered = str_replace('www', ' ', $this->filtered);
        $this->filtered = str_replace('.', ' ', $this->filtered);
        $this->filtered = str_replace('-', ' ', $this->filtered);
        $this->filtered = str_replace('/', ' ', $this->filtered);
        $this->filtered = str_replace('&', ' ', $this->filtered);
        $this->filtered = str_replace('%', ' ', $this->filtered);
        $this->filtered = str_replace('+', ' ', $this->filtered);
        $this->filtered = str_replace("=", ' ', $this->filtered);
        $this->filtered = str_replace("?", ' ', $this->filtered);
        $this->filtered = preg_replace('!\s+!', ' ', $this->filtered);

        return $this->filtered;
    }
}
