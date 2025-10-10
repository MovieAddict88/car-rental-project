<?php
// Car Rental Management System
// Root index file

// This file redirects to the main public-facing page.
// This approach helps in organizing the file structure, keeping the root directory clean.

// Include configuration to get the SITE_URL
require_once 'config/config.php';

// Redirect to the public homepage
header("Location: " . SITE_URL . "/public/index.php");
exit();
?>