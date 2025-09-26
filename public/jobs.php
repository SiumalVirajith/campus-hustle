<?php
require __DIR__ . '/partials/header.php';

global $mysqli;


$title = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? '');
$loc = trim($_GET['loc'] ?? '');
$categories = ['Software', 'Design', 'Marketing', 'Finance'];

$isStudent = $user && (($user['role'] ?? '') === 'student');
$sid = $isStudent ? (int) $user['id'] : 0;

$likeTitle = "%{$title}%";
$likeLoc = "%{$loc}%";

$appliedSelect = $isStudent
  ? ", EXISTS(SELECT 1 FROM application a WHERE a.job_id = j.id AND a.student_id = ?) AS applied"
  : ", 0 AS applied";

$sql = "SELECT j.id, j.title, j.category, j.location, j.salary, j.created_at
        {$appliedSelect}
        FROM jobs j
        WHERE j.title LIKE ? AND j.location LIKE ?";

$types = '';
$args = [];

if ($isStudent) {
  $types .= 'i';
  $args[] = $sid;
}

$types .= 'ss';
$args[] = $likeTitle;
$args[] = $likeLoc;

if ($cat !== '') {
  $sql .= " AND j.category = ?";
  $types .= 's';
  $args[] = $cat;
}

$sql .= " ORDER BY j.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$args);
$stmt->execute();
$res = $stmt->get_result();
?>
<main class="container py-4 mt-auto">
  <form class="row g-2 mb-3" action="<?= url('jobs.php') ?>" method="get">
    <div class="col-md-4">
      <input class="form-control" name="q" placeholder="Search jobs..." value="<?= e($title) ?>">
    </div>
    <div class="col-md-3">
      <select class="form-select" name="cat">
        <option value="">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= e($c) ?>" <?= ($cat === $c) ? 'selected' : '' ?>><?= e($c) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <input class="form-control" name="loc" placeholder="Location" value="<?= e($loc) ?>">
    </div>
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-gradient">Search</button>
    </div>
  </form>

  <div class="row g-3">
    <?php if ($res->num_rows === 0): ?>
      <div class="col-12">
        <div class="alert alert-dark mb-0">No jobs found. Try different keywords or location.</div>
      </div>
    <?php else: ?>
      <?php while ($job = $res->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title mb-1"><?= e($job['title']) ?></h5>
              <p class="mb-2">
                <span class="badge me-1 bg-secondary"><?= e($job['category']) ?></span>
                <span class="badge bg-dark"><?= e($job['location']) ?></span>
              </p>
              <p class="text-muted-2 small mb-3">Salary: <?= e($job['salary']) ?></p>

              <a class="btn btn-sm btn-outline-light" href="<?= url('job.php') ?>?id=<?= (int) $job['id'] ?>">View</a>

              <?php if ($isStudent): ?>
                <?php if (!empty($job['applied'])): ?>
                  <button class="btn btn-sm btn-success ms-2" disabled>
                    <i class="bi bi-check2-circle me-1"></i> Applied
                  </button>
                <?php else: ?>
                  <form method="post" action="<?= url('actions/application_apply.php') ?>" class="d-inline ms-2">
                    <?php csrf_input(); ?>
                    <input type="hidden" name="job_id" value="<?= (int) $job['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="bi bi-send-check me-1"></i> Apply
                    </button>
                  </form>
                <?php endif; ?>
              <?php else: ?>
                <a href="<?= url('login.php') ?>" class="btn btn-sm btn-success ms-2">Apply</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</main>
<?php
$stmt->close();
require __DIR__ . '/partials/footer.php';
