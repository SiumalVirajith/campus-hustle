<?php
require __DIR__ . '/../config/init.php';
require_role('employer');
require __DIR__ . '/partials/header.php';

$categories = ['Software', 'Design', 'Marketing', 'Finance'];
?>
<main class="container py-4 mt-auto" style="max-width:720px">
  <h3 class="mb-3">Post a Job</h3>
  <div class="card card-glass">
    <div class="card-body">
      <form method="post" action="<?= url('actions/job_create.php') ?>">
        <?php csrf_input(); ?>
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" required maxlength="150">
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="6" required></textarea>
        </div>
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select class="form-select" name="category" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>"><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Location</label>
            <input class="form-control" name="location" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Salary</label>
            <input class="form-control" name="salary" placeholder="Rs. 120,000">
          </div>
        </div>
        <button class="btn btn-gradient mt-3">Publish Job</button>
      </form>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>