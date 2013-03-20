<?php defined('SYS_PATH') or die('No direct script access.');

// --------------------------------------------------------------
// Set up autoloading for system files, models, and libraries.
// --------------------------------------------------------------
function autoloadSystem($className)
{
    $class = strtolower($className);
    if (file_exists($path = SYS_PATH.$class.EXT)) {
        require $path;
    }
}

function autoloadModel($className)
{
    $class = strtolower($className);
    
    // By convention models are suffixed with 'Model', so we remove the
    // suffix here so we don't need it in the file name.
    $class = str_replace('Model', '', $class);
    
    if (file_exists($path = APP_PATH.'models'.DS.$class.EXT)) {
        require $path;
    }
}

function autoloadController($className)
{
    $class = strtolower($className);
    
    // By convention models are suffixed with 'Controller', so we remove the
    // suffix here so we don't need it in the file name.
    $class = str_replace('Controller', '', $class);
    
    if (file_exists($path = APP_PATH.'controllers'.DS.$class.EXT)) {
        require $path;
    }
}

function autoloadLibrary($className)
{
    $class = strtolower($className);
    if (file_exists($path = APP_PATH.'libraries'.DS.$class.EXT)) {
        require $path;
    }
}

spl_autoload_register('autoloadSystem');
spl_autoload_register('autoloadModel');
spl_autoload_register('autoloadController');
spl_autoload_register('autoloadLibrary');