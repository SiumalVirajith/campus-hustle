<?php require __DIR__ . '/partials/header.php';
global $mysqli;
$id = (int) ($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT j.*, u.name AS employer_name FROM jobs j JOIN users u ON u.id=j.employer_id WHERE j.id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$job = $res->fetch_assoc();
$stmt->close();
if (!$job) {
  http_response_code(404);
  echo "<div class='container py-5'><div class='alert alert-warning'>Job not found</div></div>";
  require __DIR__ . '/partials/footer.php';
  exit;
}
?>
<main class="container py-4 mt-auto">
  <div class="card card-glass">
    <div class="card-body">
      <div class="d-flex justify-content-between">
        <div>
          <h3 class="card-title mb-1"><?= e($job['title']) ?></h3>
          <p class="mb-2"><span class="badge me-1 bg-secondary"><?= e($job['category']) ?></span><span
              class="badge bg-dark"><?= e($job['location']) ?></span></p>
          <p class="text-muted-2 small mb-2">Salary: <?= e($job['salary']) ?></p>
          <p class="text-muted-2 small mb-2">Posted by: <?= e($job['employer_name']) ?></p>
        </div>
      </div>
      <hr />
      <p class="mb-0"><?= nl2br(e($job['description'])) ?></p>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>