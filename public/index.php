<?php require __DIR__ . '/partials/header.php'; ?>
<main class="container py-5">
  <div class="p-4 p-md-5 mb-4 rounded-4 card-glass">
    <div class="col-lg-8 px-0">
      <h1 class="display-5 fw-bold">Find your next campus job with style</h1>

      <form class="row g-2" action="<?= url('jobs.php') ?>" method="get">
        <div class="col-md-4"><input class="form-control" name="q" placeholder="Search titles e.g., Intern"></div>
        <div class="col-md-3">
          <select class="form-select" name="cat">
            <option value="">All Categories</option>
            <option value="Software">Software</option>
            <option value="Design">Design</option>
            <option value="Marketing">Marketing</option>
            <option value="Finance">Finance</option>
          </select>
        </div>
        <div class="col-md-3"><input class="form-control" name="loc" placeholder="Location"></div>
        <div class="col-md-2 d-grid"><button type="submit" class="btn btn-gradient">Browse</button></div>
      </form>
    </div>
  </div>

  <h3 class="mb-3">Latest Jobs</h3>
  <div class="row g-3">
    <?php
    global $mysqli;
    $isStudent = $user && (($user['role'] ?? '') === 'student');

    if ($isStudent) {
      $sid = (int) $user['id'];
      $stmt = $mysqli->prepare(
        "SELECT j.id, j.title, j.category, j.location, j.salary, j.created_at,
                  EXISTS(SELECT 1 FROM application a
                         WHERE a.job_id = j.id AND a.student_id = ?) AS applied
           FROM jobs j
           ORDER BY j.created_at DESC
           LIMIT 6"
      );
      $stmt->bind_param('i', $sid);
      $stmt->execute();
      $res = $stmt->get_result();
    } else {
      $res = $mysqli->query(
        "SELECT id, title, category, location, salary, created_at, 0 AS applied
           FROM jobs
           ORDER BY created_at DESC
           LIMIT 6"
      );
    }

    if ($res && $res->num_rows):
      while ($job = $res->fetch_assoc()):
        ?>
        <div class="col-md-6 col-lg-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title"><?= e($job['title']) ?></h5>
              <p class="mb-2">
                <span class="badge me-1 bg-secondary"><?= e($job['category']) ?></span>
                <span class="badge bg-dark"><?= e($job['location']) ?></span>
              </p>
              <p class="text-muted-2 small mb-3">Salary: <?= e($job['salary']) ?></p>

              <a href="<?= url('job.php') ?>?id=<?= (int) $job['id'] ?>" class="btn btn-sm btn-outline-light">View</a>

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
        <?php
      endwhile;
    else:
      ?>
      <div class="col-12">
        <div class="alert alert-dark mb-0">No jobs yet.</div>
      </div>
    <?php endif;

    if (isset($stmt))
      $stmt->close();
    ?>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>