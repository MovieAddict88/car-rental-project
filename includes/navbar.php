<?php $cfg = app_config(); use App\Core\Auth; Auth::startSession(); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">CRMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/cars">Cars</a></li>
      </ul>
      <div class="d-flex gap-2">
        <?php if (!empty($_SESSION['user'])): ?>
          <?php if (($_SESSION['user']['role'] ?? 'user') === 'admin'): ?>
            <a class="btn btn-outline-secondary" href="/admin">Admin</a>
          <?php else: ?>
            <a class="btn btn-outline-secondary" href="/user">Dashboard</a>
          <?php endif; ?>
          <a class="btn btn-primary" href="/logout">Logout</a>
        <?php else: ?>
          <a class="btn btn-outline-primary" href="/login">Login</a>
          <a class="btn btn-primary" href="/register">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<div class="container py-4">
