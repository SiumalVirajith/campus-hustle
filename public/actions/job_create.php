<?php

require dirname(__DIR__, 2) . '/config/init.php';
require_role('employer');
csrf_verify();

$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$cat = trim($_POST['category'] ?? '');
$loc = trim($_POST['location'] ?? '');
$sal = trim($_POST['salary'] ?? '');

$allowed = ['Software', 'Design', 'Marketing', 'Finance'];
if ($title === '' || $desc === '' || $loc === '' || !in_array($cat, $allowed, true)) {
    flash('Please fill all fields correctly.', 'danger');
    header('Location: ' . url('post_job.php'));
    exit;
}

$me = current_user();
$stmt = $mysqli->prepare(
    "INSERT INTO jobs (employer_id, title, description, category, location, salary)
   VALUES (?,?,?,?,?,?)"
);
$stmt->bind_param('isssss', $me['id'], $title, $desc, $cat, $loc, $sal);
$stmt->execute();
$jid = $stmt->insert_id;
$stmt->close();

flash('Job posted!', 'success');
header('Location: ' . url('my_jobs.php'));

exit;
