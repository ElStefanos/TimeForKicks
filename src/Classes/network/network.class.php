<?php

namespace network;

use dataBase\dataBase;

class network
{
    private $db;
    public array $proxyList;
    protected int $proxyOk;
    private string $ip;
    private int $port;
    private string $auth;
    private array $headers;
    private string $target;
    private $indexId;
    private string $content;
    private string $response;
    private string $responseTime;
    private string $size;
    public array $info;
    private $curl;
    public function __construct($target, $indexId = '')
    {

        if ($indexId != '') {
            $this->indexId = $indexId;
        } else {
            $this->indexId = '';
        }

        $this->db = new dataBase;

        $this->proxyList = array();

        $this->proxyList = $this->db->grabResultsTable('kicks_networking', "WHERE `status` = '1'");

        $this->proxyOk = 0;

        if (count($this->proxyList) > 0) {
            $count = count($this->proxyList) - 1;
            $rand = rand(0, $count);
            $this->proxyOk = 1;
            $this->ip = $this->proxyList[$rand]['ip'];
            $this->port = $this->proxyList[$rand]['port'];
            $this->auth = $this->proxyList[$rand]['username_password'];
        }

        $this->target = $target;
        $this->headers[]  =  "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36";

        if (str_contains($this->target, '.aspx') !== false) {
            $this->headers[]  = "Content-type: application/json";
        } else {
            $this->headers[]  = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        }

        $this->headers[]  = "Accept-Language:en-us,en;q=0.5";
        $this->headers[]  = "Accept-Encoding:gzip,deflate";
        $this->headers[]  = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $this->headers[]  = "Keep-Alive:115";
        $this->headers[]  = "Connection:keep-alive";
        $this->headers[]  = "Cache-Control:max-age=0";

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->target);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_ENCODING, "gzip");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

        if ($this->proxyOk) {
            curl_setopt($this->curl, CURLOPT_PROXY, $this->ip . ":" . $this->port);
            curl_setopt($this->curl, CURLOPT_PROXYUSERPWD, $this->auth);
        }


        curl_setopt($this->curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
    }
    private function addToStats()
    {

        
        $result = $this->db->grabResultsTable('kicks_networking_stats', "WHERE `url` = '$this->target'");
        
        if(!is_array($result)) {
            $result = array();
        }

        if (count($result) > 0) {

            if ($this->indexId != '') {
                $this->db->updateTable('kicks_networking_stats', "`response_code`='$this->response',`response_time`='$this->responseTime',`size`='$this->size',`index_id`='$this->indexId',`date`= CURRENT_TIMESTAMP WHERE `url` = '$this->target'");
            } else {
                $this->db->updateTable('kicks_networking_stats', "`response_code`='$this->response',`response_time`='$this->responseTime',`size`='$this->size', `date`= CURRENT_TIMESTAMP WHERE `url` = '$this->target'");
            }
            return 0;
        }

        if ($this->indexId != '') {
            $this->db->insertTable('kicks_networking_stats', "(`id`, `url`, `response_code`, `response_time`, `size`, `index_id`, `date`)", "(NULL, '$this->target', '$this->response', '$this->responseTime', '$this->size', '$this->indexId', CURRENT_TIMESTAMP);");
        } else {
            $this->db->insertTable('kicks_networking_stats', "(`id`, `url`, `response_code`, `response_time`, `size`, `index_id`, `date`)", "(NULL, '$this->target', '$this->response', '$this->responseTime', '$this->size', NULL, CURRENT_TIMESTAMP);");
        }

        return 0;
    }

    private function addToDailyStats()
    {   
        $date = date('Y-m-d');
        $result = $this->db->grabResultsTable('kicks_networking_daily_stats', "WHERE `date` = '$date'");

        if($this->size == -1) {
            $this->size = 1048576;
        }

        $totalRequests = 1;

        if(!is_array($result)) {
            $result = array();
        }

        if (count($result) > 0) {

            $errors = $result[0]['errors'];
            $successes = $result[0]['successes'];
            $bandwith = $this->size + $result[0]['bandwith'];
            $totalRequests += $result[0]['requests'];

            if($this->response >= 200 && $this->response < 300) {
                $successes++;
            } else {
                $errors++;
            }

            $this->db->updateTable('kicks_networking_daily_stats', "`requests` = '$totalRequests',`bandwith` = '$bandwith',`successes` = '$successes',`errors` = '$errors' WHERE `date` = '$date'");

            return 1;
        }

        $successes = 0;
        $errors = 0;

        if($this->response >= 200 && $this->response < 300) {
            $successes++;
        } else {
            $errors++;
        }

        $bandwith = $this->size;
        $this->db->insertTable('kicks_networking_daily_stats', "(`id`, `requests`, `bandwith`, `successes`, `errors`, `date`)", "(NULL, '$totalRequests', '$bandwith', '$successes', '$errors', '$date');");

        return 1;
    }

    public function curlTarget($id = '', $stats = 1)
    {
        $this->content = (string) curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);
        $this->responseTime = $this->info['total_time'];
        $this->response = $this->info['http_code'];
        $this->size = $this->info['download_content_length'];
        
        if($stats == 1) {
            $this->addToStats();
        }
        $this->addToDailyStats();


        curl_close($this->curl);

        return $this->content;
    }

    public function forceProxy($ip, $port, $auth)
    {   
        $this->ip = $ip;
        $this->port = $port;
        $this->auth = $auth;
        curl_setopt($this->curl, CURLOPT_PROXY, $this->ip . ":" . $this->port);
        curl_setopt($this->curl, CURLOPT_PROXYUSERPWD, $this->auth);
    }

    public function getCode()
    {   
        return $this->response;
    }
}
