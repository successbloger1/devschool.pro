<?php

spl_autoload_register('autoload');

function autoload($className) {
    $fileName = __DIR__ . '/lib/' .$className . '.php';
    if (file_exists($fileName)) {
        require_once($fileName);
        return true;
    }
    return false;
}

?>