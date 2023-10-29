<?php
namespace database;

use mysqli;

class dataBaseConfig {
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    
    public function __construct() {

        $this->host = "localhost";
        $this->database = "timeforkicks2_development";
        $this->username = "timeforkicks2_root";
        $this->password = "Testiranje1";
    }

    public function getDetails() {
        return array(
            "hostname" => $this->host,
            "database" => $this->database,
            "username" => $this->username,
            "password" => $this->password
        );
    }

}