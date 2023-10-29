<?php

    namespace dashboard;

    class getModules
    {
        public function loadModule($mod) {
            $modFile = $mod.".mod.php";
            $modFilePath = __MODULES__.$mod."/".$modFile;
            if(file_exists($modFilePath)){
                include_once $modFilePath;
            } else {
                die("Could not load module $mod on $modFilePath");
            }
        }
    }