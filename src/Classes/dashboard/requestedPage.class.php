<?php

    namespace dashboard;


    class requestedPage {
        private $page;

        public function __construct()
        {
            if(isset($_GET['page'])) {
                $this->page = $_GET['page'];
                $page = $_GET['page'];
                $this->page = __URL__."/dashboard?page=".$page;
            }
        }

        public function returnPage() {
            return $this->page;
        }

    }