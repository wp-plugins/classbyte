<?php
namespace CB;

function spl_autoloader($class)
{
    $file = CB_DIR . 'classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        include_once $file;
    }
}

spl_autoload_register(__NAMESPACE__ . '\spl_autoloader');