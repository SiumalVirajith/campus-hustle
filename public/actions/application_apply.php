<?php
require dirname(__DIR__, 2) . '/config/init.php';

$BASE = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
if ($BASE === '.' || $BASE === '\\')
    $BASE = '';

function back_to_job($jobId, $msg = null, $type = 'success')
{
    global $BASE;
    if ($msg && function_exists('flash'))
        flash($msg, $type);
    header('Location: ' . $BASE . '/job.php?id=' . (int) $jobId);
    exit;
}

if (function_exists('csrf_verify'))
    csrf_verify();


$me = current_user();
if (!$me || ($me['role'] ?? '') !== 'student') {
    header('Location: ' . $BASE . '/login.php');
    exit;
}

$jobId = (int) ($_POST['job_id'] ?? 0);
if ($jobId <= 0)
    back_to_job($jobId, 'Invalid job.', 'danger');

// Ensure job exists
$stmt = $mysqli->prepare("SELECT id FROM jobs WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $jobId);
$stmt->execute();
$exists = $stmt->get_result()->fetch_column();
$stmt->close();
if (!$exists)
    back_to_job($jobId, 'Job not found.', 'danger');

// Insert application (unique on job_id + student_id)
try {
    $stmt = $mysqli->prepare("INSERT INTO application (job_id, student_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $jobId, $me['id']);
    $stmt->execute();
    $stmt->close();
    back_to_job($jobId, 'Application submitted!');
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062)
        back_to_job($jobId, 'You already applied to this job.', 'info');
    back_to_job($jobId, 'Database error: ' . $e->getMessage(), 'danger');
}
