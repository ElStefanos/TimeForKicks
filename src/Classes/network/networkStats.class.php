<?php

namespace network;

use dataBase\dataBase;

class networkStats 
{    
    private $db;
    private $result;
    public $errors;
    public $totalRequests;
    public $totalEstimatedBandwith;
    public $successfullRequests;

    public $avgTime;
    
    public function __construct()
    {
        $this->db = new dataBase;
        $this->result = $this->db->grabResultsTable('kicks_networking_stats');
        $this->totalRequests = count($this->result);
        $this->successfullRequests = 0;
        $this->errors = 0;
        $this->totalEstimatedBandwith = 0;
        $this->totalRequests = 0;
        $this->avgTime = 0;
    }

    public function lastNetworkStats()
    {
        foreach ($this->result as $key => $value) {
            $this->totalRequests++;
            if($value['response_code'] >= 200 && $value['response_code'] < 300) {
                $this->successfullRequests++;
            } else {
                $this->errors++;
            }
        }

        foreach ($this->result as $key => $value) {
            $this->avgTime += $value['response_time'];
        }

        if($this->avgTime > 0) {

            $this->avgTime /= $this->totalRequests;
        }

        $this->avgTime = round($this->avgTime, 2);

        foreach ($this->result as $key => $value) {
            if($value['size'] == -1) {
                $value['size'] = 1048576;
            }
            $this->totalEstimatedBandwith = $this->totalEstimatedBandwith + $value['size'];
        }

        $this->totalEstimatedBandwith = round($this->totalEstimatedBandwith / pow(1024, 3), 2)."GB";
    }

    public function dailyStats()
    {   
        $date = date('Y-m-d');
        $this->result = $this->db->grabResultsTable('kicks_networking_daily_stats', "WHERE `date` = '$date'");

        if(empty($this->result)) return; 

        $this->totalEstimatedBandwith = $this->result[0]['bandwith'];
        $this->totalEstimatedBandwith = round($this->totalEstimatedBandwith / pow(1024, 3), 2)."GB";
        $this->successfullRequests = $this->result[0]['successes'];
        $this->errors = $this->result[0]['errors'];
        $this->totalRequests = $this->result[0]['requests'];
    }

    public function dailyStatsOverall()
    {
        $this->result = $this->db->grabResultsTable('kicks_networking_daily_stats');

        if(empty($this->result)) return; 

        foreach ($this->result as $key => $value) {
            $stats[$value['date']] = $value;
        }

        return $stats;
    }

}