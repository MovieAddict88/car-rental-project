<?php
// This file can be included at the top of every admin page to ensure the user is an admin.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // If not an admin, redirect to the main login page or homepage.
    // We can add a message to indicate insufficient permissions.
    $_SESSION['error_message'] = "You do not have permission to access the admin area.";

    // Assuming URL_ROOT is defined from a config file that should be included before this script.
    // If config.php is not yet included, this might fail. Let's ensure it's included in admin pages.
    if (defined('URL_ROOT')) {
        header("Location: " . URL_ROOT . "/user/login.php");
    } else {
        // Fallback if URL_ROOT is not available
        header("Location: /user/login.php");
    }
    exit();
}

// If the script reaches here, the user is a logged-in admin.
?>