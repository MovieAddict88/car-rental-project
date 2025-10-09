<?php
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../user/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Car Rental</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">
    <div class="bg-dark border-right p-3" id="sidebar">
        <h3 class="text-white">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="cars.php">Manage Cars</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="bookings.php">Manage Bookings</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="users.php">Manage Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="payments.php">Manage Payments</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="reports.php">Reports</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="settings.php">Settings</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../user/logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container-fluid">
        <div class="content">