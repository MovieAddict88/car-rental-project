<?php use App\Core\Auth; Auth::startSession(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/admin">CRMS Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/admin/cars">Cars</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/bookings">Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/payments">Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/reports">Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/settings">Settings</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/feedback">Feedback</a></li>
      </ul>
      <div class="d-flex gap-2 align-items-center">
        <span class="text-white-50 small"><?= htmlspecialchars(Auth::user()['email'] ?? '') ?></span>
        <a class="btn btn-light btn-sm" href="/logout">Logout</a>
      </div>
    </div>
  </div>
</nav>
<div class="container py-4">
