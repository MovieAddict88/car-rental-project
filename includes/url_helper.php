<?php
// Simple page redirect
function redirect($page){
    header('location: ' . SITE_URL . '/' . $page);
    exit;
}