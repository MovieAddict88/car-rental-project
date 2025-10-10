<?php
// Load Config
require_once(dirname(__DIR__) . '/config/config.php');

// Load Helpers
require_once('session_helper.php');
require_once('url_helper.php');
require_once('email_helper.php');

// Define App Root
define('APPROOT', dirname(__DIR__));

// Autoload Core Libraries
spl_autoload_register(function($className){
    // The autoloader needs to look in the 'libraries' directory
    $file = APPROOT . '/libraries/' . $className . '.php';
    if(file_exists($file)){
        require_once $file;
    }
});
?>