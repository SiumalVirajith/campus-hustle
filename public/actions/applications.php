<?php require __DIR__ . '/partials/header.php'; ?>

<?php

if (!$user || ($user['role'] ?? '') !== 'employer') {
    if (function_exists('flash'))
        flash('Unauthorized', 'danger');
    header('Location: ' . url('index.php'));
    exit;
}

// Fetch this employer's applications
$stmt = $mysqli->prepare(
    "SELECT a.id AS app_id, a.status, a.applied_at,
          u.name AS student_name, u.email AS student_email,
          j.title AS job_title
   FROM application a
   JOIN users u ON u.id = a.student_id
   JOIN jobs  j ON j.id = a.job_id
   WHERE j.employer_id = ?
   ORDER BY a.applied_at DESC"
);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$res = $stmt->get_result();
?>

<main class="container py-4">
    <h3 class="mb-3">Applications</h3>

    <div class="table-responsive card card-glass">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Job</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th style="width:220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No applications yet.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= e($row['student_name']) ?></div>
                                <div class="text-muted-2 small"><?= e($row['student_email']) ?></div>
                            </td>
                            <td><?= e($row['job_title']) ?></td>
                            <td><?= e($row['applied_at']) ?></td>
                            <td>
                                <?php
                                $status = $row['status'] ?? 'pending';
                                $badge = ['pending' => 'secondary', 'accepted' => 'success', 'rejected' => 'danger'][$status] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= e(ucfirst($status)) ?></span>
                            </td>
                            <td>
                                <?php if ($status === 'pending'): ?>
                                    <form method="post" action="<?= url('actions/application_decide.php') ?>" class="d-inline">
                                        <?php csrf_input(); ?>
                                        <input type="hidden" name="app_id" value="<?= (int) $row['app_id'] ?>">
                                        <button type="submit" name="do" value="accept" class="btn btn-success btn-sm me-2">
                                            <i class="bi bi-check2-circle me-1"></i> Accept
                                        </button>
                                        <button type="submit" name="do" value="reject" class="btn btn-danger btn-sm">
                                            <i class="bi bi-x-circle me-1"></i> Reject
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-outline-light btn-sm" disabled>
                                        <i class="bi bi-lock"></i> Decided
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
$stmt->close();
require __DIR__ . '/partials/footer.php';
