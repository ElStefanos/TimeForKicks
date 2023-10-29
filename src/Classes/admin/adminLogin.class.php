<?php

namespace admin;

use dataBase\dataBaseConfig;
use admin\adminRegister;
use mysqli;

class adminLogin
{

    private string $email;
    private string $password;
    private string $hash;
    private string $username;
    private string $date;
    private int $id;
    private int $status;
    private array $params;
    private object $mysqli;
    private object $register;

    public function __construct($email, $password)
    {
        $config = new dataBaseConfig;
        $this->params = $config->getDetails();
        $this->mysqli = new mysqli($this->params['hostname'], $this->params['username'], $this->params['password'], $this->params['database']);
        $this->password = $password;
        $this->email = $email;
        $this->username = "";
        $this->date = "";
        $this->hash = "";
        $this->status = 0;
        $this->id = 0;

        if(empty($this->email) || empty($this->password)) {
            header("Location: ".__URL__."?error=empty_credentials");
            exit();
        } 

        $stmt = $this->mysqli->prepare("SELECT * FROM `kicks_admin`");
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows == 0) {
            $this->register = new adminRegister($this->email, $this->password, 'Admin');

            if($this->register->createUser()) {
                header("Location: ".__URL__.'/dashboard?page=dashboard');
                exit();
            } else {
                header("Location: ".__URL__.'/dashboard?page=dashboard&&acount_exists');
                exit();
            }
            
        } else {
            $this->checkCredentials();
        }
        $stmt->close();

    }

    private function checkCredentials(){

        $stmt = $this->mysqli->prepare("SELECT * FROM `kicks_admin` WHERE `email` = ? AND `active` = ?");
        $status = 1;
        $stmt->bind_param('ss', $this->email, $status);
        $stmt->execute();
        $this->hash = 0;
        $this->id = 0;
        $this->status = 0;
        $stmt->bind_result($this->id, $this->status, $this->email, $this->username, $this->hash, $this->date);
        $stmt->store_result();
        if($stmt->num_rows == 1) {
            $stmt->fetch();
            $this->password = hash("SHA512", $this->password);
            if ($this->password == $this->hash) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['signed'] = 1;
                $_SESSION['username'] = $this->username;
                $_SESSION['status'] = $this->status;
                $_SESSION['id'] = $this->id;
                $_SESSION['date'] = $this->date;

                $stmt->close();

                header("Location: ".__URL__.'/dashboard?page=dashboard');
                exit();

            } else {

                $stmt->close();

                header("Location: ".__URL__.'?login=failed');
               exit();
            }
        } else {

            $stmt->close();

            header("Location: ".__URL__.'?login=failed');
           exit();
        }
    }
}
