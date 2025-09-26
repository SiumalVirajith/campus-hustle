<?php require __DIR__.'/partials/header.php'; require_role('student'); global $mysqli;
$job_id = (int)($_GET['job_id'] ?? 0);
$stmt = $mysqli->prepare("SELECT id,title FROM jobs WHERE id=?");
$stmt->bind_param('i', $job_id);
$stmt->execute(); $res = $stmt->get_result();
$job = $res->fetch_assoc(); $stmt->close();
if (!$job) { echo "<div class='container py-5'><div class='alert alert-warning'>Job not found</div></div>"; require __DIR__.'/partials/footer.php'; exit; }
?>
<main class="container py-4 mt-auto">
  <div class="card card-glass">
    <div class="card-body">
      <h3 class="card-title">Apply: <?= e($job['title']) ?></h3>
      <form method="post" action="/actions/application_create.php" class="mt-3">
        <?php csrf_input(); ?>
        <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
        <div class="mb-3">
          <label class="form-label">Cover Letter (optional)</label>
          <textarea class="form-control" name="cover" rows="6" placeholder="Write a short message..."></textarea>
        </div>
        <button class="btn btn-gradient">Submit Application</button>
      </form>
    </div>
  </div>
</main>
<?php require __DIR__.'/partials/footer.php'; ?>