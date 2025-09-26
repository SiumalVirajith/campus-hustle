<?php

require_once __DIR__ . '/../../config/init.php';


if (isset($_GET['logout']) || (isset($_POST['do']) && $_POST['do'] === 'logout')) {
  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && function_exists('csrf_verify')) {
    csrf_verify();
  }
  unset($_SESSION['user_id']);
  session_regenerate_id(true);
  if (function_exists('flash'))
    flash('Signed out successfully', 'success');
  header('Location: ' . url('index.php'));
  exit;
}


$CURRENT = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if (!function_exists('nav_active')) {
  function nav_active(string $file): string
  {
    global $CURRENT;
    return ($CURRENT === strtolower($file)) ? ' active' : '';
  }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Campus Hustle</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- App CSS -->
  <link href="<?= url('assets/app.css') ?>" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="<?= url('assets/favicon.svg') ?>">

</head>

<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-dark navbar-glass sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="<?= url('index.php') ?>">
        <img src="<?= url('assets/logo-wordmark.svg') ?>" alt="Campus Hustle" height="28" class="d-none d-md-inline">
        <img src="<?= url('assets/logo-mark.svg') ?>" alt="Campus Hustle" height="28" class="d-inline d-md-none">
      </a>


      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link<?= nav_active('index.php') ?>" href="<?= url('index.php') ?>">
              <i class="bi bi-house-door me-1"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= nav_active('jobs.php') ?>" href="<?= url('jobs.php') ?>">
              <i class="bi bi-briefcase me-1"></i> Jobs
            </a>
          </li>

          <?php if ($user && ($user['role'] ?? '') === 'employer'): ?>
            <li class="nav-item">
              <a class="nav-link<?= nav_active('post_job.php') ?>" href="<?= url('post_job.php') ?>">
                <i class="bi bi-plus-square me-1"></i> Post Job
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= nav_active('applications.php') ?>" href="<?= url('applications.php') ?>">
                <i class="bi bi-inbox me-1"></i> Applications
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= nav_active('my_jobs.php') ?>" href="<?= url('my_jobs.php') ?>">
                <i class="bi bi-card-checklist me-1"></i> My Jobs
              </a>
            </li>
          <?php endif; ?>
        </ul>

        <div class="d-flex align-items-center gap-2">
          <?php if ($user): ?>
            <div class="dropdown">
              <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center"
                data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>
                <span class="text-truncate" style="max-width: 160px;">
                  <?= e($user['name']) ?> (<?= e($user['role']) ?>)
                </span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <hr class="dropdown-divider">
                </li>

                <!-- Simple GET logout -->
                <li>
                  <a class="dropdown-item" href="<?= url('index.php') ?>?logout=1">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign out
                  </a>
                </li>
              </ul>
            </div>
          <?php else: ?>
            <a class="btn btn-outline-light btn-sm" href="<?= url('login.php') ?>">
              <i class="bi bi-box-arrow-in-right me-1"></i> Sign in
            </a>
            <a class="btn btn-gradient btn-sm" href="<?= url('register.php') ?>">
              <i class="bi bi-person-plus me-1"></i> Register
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <?php if ($flashes = get_flashes()): ?>
    <div class="container mt-3">
      <?php foreach ($flashes as $f): ?>
        <div class="alert alert-<?= e($f['type']) ?> alert-dismissible fade show" role="alert">
          <?= e($f['msg']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>