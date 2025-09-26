<?php
require dirname(__DIR__, 2) . '/config/init.php';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: ' . url('my_jobs.php'));
    exit;
}
require_role('employer');
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash('Invalid job id', 'danger');
    header('Location: ' . url('my_jobs.php'));
    exit;
}

$stmt = $mysqli->prepare("DELETE FROM jobs WHERE id=? AND employer_id=?");
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();

flash($deleted ? 'Job deleted.' : 'Job not found or not yours.', $deleted ? 'success' : 'warning');
header('Location: ' . url('my_jobs.php'));
exit;
