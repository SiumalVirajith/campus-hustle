<?php

require __DIR__ . '/../config/init.php';


if (!empty($user)) {

  $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  if ($BASE === '.' || $BASE === '\\')
    $BASE = '';
  header('Location: ' . $BASE . '/index.php');
  exit;
}


$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($BASE === '.' || $BASE === '\\')
  $BASE = '';


require __DIR__ . '/partials/header.php';
?>
<main class="container py-5 mt-auto" style="max-width:580px">
  <div class="card card-glass">
    <div class="card-body">
      <h3 class="card-title">Sign in</h3>

      <?php if (!empty($_GET['err'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['err'], ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="post" action="<?= $BASE ?>/actions/auth_login.php" class="mt-3">
        <?php if (function_exists('csrf_input'))
          csrf_input(); ?>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required placeholder="you@example.com">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-gradient">Sign in</button>
        <p class="mt-3 text-muted-2">
          No account? <a href="<?= $BASE ?>/register.php" class="link-light">Register</a>
        </p>
      </form>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>