<?php

    namespace admin;

    class adminCheck {

        public function checkAdmin() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['signed'])) {
                header("Location: ".__URL__);
                exit();
            }
        }

        public function getLoginPage() {
            include_once __MODULES__.'login'.DIRECTORY_SEPARATOR.'login.mod.php';
        }

    }
?>