<?php

namespace admin;
use dataBase\dataBaseConfig;
use mysqli;

class adminRegister
{
    private string $email;
    private string $password;
    private string $hash;
    private string $username;
    private int $status;
    private object $mysqli;
    private array $params;

    public function __construct($email, $password, $username)
    {
        $this->email = $email;
        $this->password = $password;

        if (strlen($this->password) < 8) {
            header("Location: " . __URL__ . '?register=failed&password_to_short');
            exit();
        }
        $this->username = $username;

        $this->hash = hash('SHA512', $this->password);
        $this->status = 1;
        $config = new dataBaseConfig();
        $this->params = $config->getDetails();
        $this->mysqli = new mysqli($this->params['hostname'], $this->params['username'], $this->params['password'], $this->params['database']);
    }

    public function createUser()
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM `kicks_admin` WHERE `email` = ?");
        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $stmt->close();
            $stmt = $this->mysqli->prepare("INSERT INTO `kicks_admin` (`active`, `email`, `username`, `password`) VALUES ( ?, ?, ?, ?)");

            $stmt->bind_param("isss", $this->status, $this->email, $this->username, $this->hash);

            $stmt->execute();
            $stmt->close();
            
            return 1;

        } else {
            return 0;
        }
    }
}
