<?php
require __DIR__ . '/../../config/init.php';
require_role('student');
verify_csrf();
global $mysqli;
$user = current_user();

$job_id = (int) ($_POST['job_id'] ?? 0);
$cover = trim($_POST['cover'] ?? '');

# ensure job exists
$stmt = $mysqli->prepare("SELECT id FROM jobs WHERE id=?");
$stmt->bind_param('i', $job_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    flash('danger', 'Job does not exist');
    header('Location: /jobs.php');
    exit;
}
$stmt->close();

# prevent duplicate applications
$stmt = $mysqli->prepare("SELECT id FROM application WHERE job_id=? AND student_id=?");
$stmt->bind_param('ii', $job_id, $user['id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    flash('warning', 'You have already applied');
    header("Location: /job.php?id=" . $job_id);
    exit;
}
$stmt->close();

$stmt = $mysqli->prepare("INSERT INTO application (job_id, student_id) VALUES (?,?)");
$stmt->bind_param('ii', $job_id, $user['id']);
$stmt->execute();
$stmt->close();

flash('success', 'Application submitted');
header('Location: /job.php?id=' . $job_id);
exit;