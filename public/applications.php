<?php
require __DIR__ . '/../config/init.php';
require_role('employer');
require __DIR__ . '/partials/header.php';

global $mysqli;
$user = current_user();
?>
<main class="container py-4 flex-grow-1 mt-auto">
  <h3 class="mb-3">Applications</h3>
  <div class="table-responsive">
    <table class="table align-middle table-hover table-rounded">
      <thead>
        <tr>
          <th>Student</th>
          <th>Email</th>
          <th>Job</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $mysqli->prepare(
          "SELECT a.applied_at, j.title, s.name AS student_name, s.email AS student_email
             FROM application a
             JOIN jobs j ON j.id = a.job_id
             JOIN users s ON s.id = a.student_id
            WHERE j.employer_id = ?
         ORDER BY a.applied_at DESC"
        );
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= e($row['student_name']) ?></td>
            <td><?= e($row['student_email']) ?></td>
            <td><?= e($row['title']) ?></td>
            <td><small class="text-muted-2"><?= e($row['applied_at']) ?></small></td>
          </tr>
        <?php endwhile;
        $stmt->close(); ?>
      </tbody>
    </table>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>