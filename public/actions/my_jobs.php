<?php require __DIR__ . '/partials/header.php'; ?>
<?php
if (!$user || ($user['role'] ?? '') !== 'employer') {
  if (function_exists('flash'))
    flash('Unauthorized', 'danger');
  header('Location: ' . url('index.php'));
  exit;
}

$sql = "SELECT j.id, j.title, j.category, j.location, j.created_at,
               (SELECT COUNT(*) FROM application a WHERE a.job_id=j.id) AS apps,
               (SELECT COUNT(*) FROM application a WHERE a.job_id=j.id AND a.status='accepted') AS accepted
        FROM jobs j
        WHERE j.employer_id=?
        ORDER BY j.created_at DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$res = $stmt->get_result();
?>
<main class="container py-4">
  <h3 class="mb-3">My Jobs</h3>
  <div class="row g-3">
    <?php if ($res->num_rows === 0): ?>
      <div class="col-12">
        <div class="alert alert-dark">No jobs yet. <a href="<?= url('post_job.php') ?>">Post one</a>.</div>
      </div>
    <?php else:
      while ($j = $res->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title mb-1"><?= e($j['title']) ?></h5>
              <p class="mb-2">
                <span class="badge me-1 bg-secondary"><?= e($j['category']) ?></span>
                <span class="badge bg-dark"><?= e($j['location']) ?></span>
              </p>
              <p class="text-muted-2 small mb-3">
                Applicants: <?= (int) $j['apps'] ?> · Accepted: <?= (int) $j['accepted'] ?>
              </p>
              <a class="btn btn-sm btn-outline-light" href="<?= url('job.php') ?>?id=<?= (int) $j['id'] ?>">View</a>
              <a class="btn btn-sm btn-primary ms-2" href="<?= url('applications.php') ?>">Applications</a>
            </div>
          </div>
        </div>
      <?php endwhile; endif; ?>
  </div>
</main>
<?php
$stmt->close();
require __DIR__ . '/partials/footer.php';
