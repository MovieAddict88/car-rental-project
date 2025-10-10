<?php
// Load configuration and bootstrap
if (!file_exists('config/config.php')) {
    header("Location: install/setup.php");
    exit;
}

// Require bootstrap file
require_once 'includes/bootstrap.php';

// Init Core Library
$init = new Core;
?>