<?php

//app timezone
date_default_timezone_set('Asia/Colombo');

// --- Database ---------------------------------------------------------------
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'campus_hustle';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$mysqli->set_charset('utf8mb4');

// --- Sessions ---------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {

  session_start();
}

// --- Helpers ----------------------------------------------------------------
if (!function_exists('e')) {
  function e($v)
  {
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
  }
}

// Flash messages
if (!function_exists('flash')) {
  function flash(string $msg, string $type = 'success'): void
  {
    $_SESSION['flashes'][] = ['msg' => $msg, 'type' => $type];
  }
}
if (!function_exists('get_flashes')) {
  function get_flashes(): array
  {
    $f = $_SESSION['flashes'] ?? [];
    unset($_SESSION['flashes']);
    return $f;
  }
}

// CSRF (simple)
if (!function_exists('csrf_token')) {
  function csrf_token(): string
  {
    if (empty($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
  }
}
if (!function_exists('csrf_input')) {
  function csrf_input(): void
  {
    echo '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
  }
}
if (!function_exists('csrf_verify')) {
  function csrf_verify(): void
  {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST')
      return;
    $sess = $_SESSION['csrf'] ?? '';
    $sent = $_POST['csrf'] ?? '';
    if ($sess === '' || $sent === '' || !hash_equals($sess, $sent)) {

      if (function_exists('flash'))
        flash('Session expired. Please try again.', 'danger');
      header('Location: ' . url('post_job.php'));
      exit;
    }
  }

}
// Alias some older code might call
if (!function_exists('csrf_validate')) {
  function csrf_validate(): void
  {
    csrf_verify();
  }
}

// Subfolder-safe URL helper (works from /public, /public/actions, /public/partials)
if (!function_exists('url')) {
  function url(string $path = ''): string
  {
    // Always anchor base to ".../public"
    $sn = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // e.g. /campus-hustle-bootstrap-mysqli/public/post_job.php
    if (preg_match('#^(.*?/public)(?:/|$)#', $sn, $m)) {
      $base = $m[1];  // "/campus-hustle-bootstrap-mysqli/public"
    } else {
      // Fallback: current script's directory
      $base = rtrim(str_replace('\\', '/', dirname($sn)), '/');
    }
    return rtrim($base, '/') . '/' . ltrim($path, '/');
  }
}


// Current user
if (!function_exists('current_user')) {
  function current_user(): ?array
  {
    if (empty($_SESSION['user_id']))
      return null;
    static $cache = null; // avoid repeat queries
    if ($cache !== null)
      return $cache;

    global $mysqli;
    $id = (int) $_SESSION['user_id'];
    $stmt = $mysqli->prepare('SELECT id, name, email, role FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $cache = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $cache;
  }
}

// Convenience: make $user available after requiring init.php
$user = current_user();

// Auth guards
if (!function_exists('require_login')) {
  function require_login(): void
  {
    $u = current_user();
    if (!$u) {
      if (function_exists('flash'))
        flash('Please sign in first', 'warning');
      header('Location: ' . url('login.php'));
      exit;
    }
  }
}
if (!function_exists('require_role')) {
  function require_role(string $role): void
  {
    $u = current_user();
    if (!$u || (($u['role'] ?? '') !== $role)) {
      if (function_exists('flash'))
        flash('Unauthorized', 'danger');
      header('Location: ' . url('index.php'));
      exit;
    }
  }
}
