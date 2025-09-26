<?php
require __DIR__ . '/../config/init.php';
if (!empty($user)) {
  header('Location: ' . url('index.php'));
  exit;
}
require __DIR__ . '/partials/header.php';

$pref = ($_GET['role'] ?? '') === 'employer' ? 'employer' : 'student';
?>
<main class="container py-5 mt-auto" style="max-width:680px">
  <div class="card card-glass">
    <div class="card-body">
      <h3 class="card-title">Create account</h3>

      <?php if (!empty($_GET['err'])): ?>
        <div class="alert alert-danger mt-3 mb-0"><?= e($_GET['err']) ?></div>
      <?php elseif (!empty($_GET['ok'])): ?>
        <div class="alert alert-success mt-3 mb-0"><?= e($_GET['ok']) ?></div>
      <?php endif; ?>

      <form method="post" action="<?= url('actions/auth_register.php') ?>" class="mt-3">
        <?php csrf_input(); ?>
        <div class="mb-2">
          <label class="form-label me-3">Role</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="role" value="student" required
              <?= $pref === 'student' ? 'checked' : '' ?>>
            <label class="form-check-label">Student</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="role" value="employer" required
              <?= $pref === 'employer' ? 'checked' : '' ?>>
            <label class="form-check-label">Employer</label>
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input class="form-control" name="name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required minlength="6">
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="password2" required minlength="6">
          </div>
        </div>

        <button type="submit" class="btn btn-gradient mt-3">Create account</button>
      </form>

      <div class="mt-3 small text-muted-2">
        Quick links:
        <a href="<?= url('register.php') ?>?role=student">Register as Student</a> ·
        <a href="<?= url('register.php') ?>?role=employer">Register as Employer</a>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>