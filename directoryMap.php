<?php

    define("__ROOT__", __DIR__);
    define("__SRC__",__ROOT__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR);
    define("__CLASSES__",__SRC__."Classes".DIRECTORY_SEPARATOR);
    define("__FUNCTIONS__",__SRC__."functions".DIRECTORY_SEPARATOR);
    define("__CRAWLERS__",__SRC__."crawlers".DIRECTORY_SEPARATOR);
    define("__EVENTS__",__SRC__."events".DIRECTORY_SEPARATOR);

    define("__VENDOR__",__ROOT__.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR);

    define("__DATA__",__ROOT__.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR);
    define("__CACHE__",__DATA__."cache".DIRECTORY_SEPARATOR);

    define("__DASHBOARD__",__ROOT__.DIRECTORY_SEPARATOR."dashboard".DIRECTORY_SEPARATOR);
    define("__MODULES__",__DASHBOARD__.'modules'.DIRECTORY_SEPARATOR);
    define("__PAGES__",__DASHBOARD__.'pages'.DIRECTORY_SEPARATOR);
    
    if(isset($_SERVER['HTTP_HOST'])) {
    
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

    define('__URL__', $actual_link);
}
?>