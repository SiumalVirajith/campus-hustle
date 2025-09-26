<?php
require __DIR__ . '/../config/init.php'; 
require_role('employer');               

global $mysqli;
$user = current_user();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM jobs WHERE id=? AND employer_id=? LIMIT 1");
$stmt->bind_param('ii', $id, $user['id']);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) {
  flash('Job not found', 'warning');
  header('Location: ' . url('my_jobs.php'));
  exit;
}

$categories = ['Software', 'Design', 'Marketing', 'Finance'];

require __DIR__ . '/partials/header.php';
?>
<main class="container py-4 mt-auto" style="max-width:800px">
  <div class="card card-glass">
    <div class="card-body">
      <h3 class="card-title">Edit Job</h3>

      <form class="mt-3" method="post" action="<?= url('actions/job_update.php') ?>">
        <?php csrf_input(); ?>
        <input type="hidden" name="id" value="<?= (int) $job['id'] ?>">

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" value="<?= e($job['title']) ?>" required maxlength="150">
        </div>

        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select class="form-select" name="category" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>" <?= $job['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Location</label>
            <input class="form-control" name="location" value="<?= e($job['location']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Salary</label>
            <input class="form-control" name="salary" value="<?= e($job['salary']) ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="6" required><?= e($job['description']) ?></textarea>
        </div>

        <button class="btn btn-gradient">Save Changes</button>
        <a href="<?= url('my_jobs.php') ?>" class="btn btn-outline-light">Cancel</a>
      </form>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>