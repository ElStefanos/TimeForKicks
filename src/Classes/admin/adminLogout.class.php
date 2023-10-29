<?php

namespace admin;

class adminLogout {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        foreach ($_SESSION as $key => $value) {
            unset($_SERVER[$key]);
        }

        session_destroy();

        header("Location: ".__URL__);
        exit();

    }
}