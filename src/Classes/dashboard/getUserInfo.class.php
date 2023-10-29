<?php

    namespace dashboard;


    class getUserInfo
    {
        private array $userInfo;

        public function __construct()
        {   
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $username = $_SESSION['username'];
            $status = $_SESSION['status'];

            $this->userInfo = array('username' => $username, 'status' => $status);

        }

        public function getInfo()
        {
            return $this->userInfo;
        }
    }