<?php

    namespace dashboard;

    class  getPages
    {
        private $page;
        public function __construct()
        {
            if(isset($_GET['page'])) {
                $this->page = $_GET['page'];
                $page = $_GET['page'];
                if(file_exists(__PAGES__.$page.DIRECTORY_SEPARATOR.$page.'.php')) {
                    include_once __PAGES__.$page.DIRECTORY_SEPARATOR.$page.'.php';
                } else {
                    die("404 Not Found");
                }
            }
        }

    }
    
