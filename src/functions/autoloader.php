<?php
function autoload($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filename = __CLASSES__ .$className . '.class.php';
    
    if (file_exists($filename)) {
      require_once($filename);
    } else {
        die("Error: $filename not found");
    }
}

spl_autoload_register('autoload');