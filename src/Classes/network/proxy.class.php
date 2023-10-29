<?php

namespace network;

use dataBase\dataBase;
use network\network;

class proxy
{
    private $db;
    private $network;
    private string $username;
    private string $password;
    private string $auth;
    private string $ip;
    private int $port;

    public function __construct($username = '', $password = '', $ip, $port, $auth = '')
    {
        $this->username = $username;
        $this->password = $password;
        if ($auth != '') {
            $this->auth = $auth;
        }

        if ($auth == '' && $this->username != '' && $this->password != '') {
            $this->auth = $this->username . ":" . $this->password;
        }

        $this->network = new network(__URL__);
        $this->ip = $ip;
        $this->port = $port;

        $this->db = new dataBase;
    }

    public function testProxy()
    {
        $this->network->forceProxy($this->ip, $this->port, $this->auth);
        $this->network->curlTarget(1, 0);
        return $this->network->getCode();
    }

    public function addProxy()
    {   
        $test = $this->testProxy();
        if($test >= 200 && $test <= 299) {
            $this->db->insertTable('kicks_networking', "(`id`, `ip`, `port`, `username_password`, `status`)", "(NULL, '$this->ip', '$this->port', '$this->auth', '1');");
            return 1;
        } else {
            return $test;
        }
    }
}
