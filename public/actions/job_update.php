<?php

require dirname(__DIR__, 2) . '/config/init.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: ' . url('my_jobs.php'));
    exit;
}

require_role('employer');
csrf_verify();

global $mysqli;
$user = current_user();

$id = (int) ($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$category = trim($_POST['category'] ?? '');
$location = trim($_POST['location'] ?? '');
$salary = trim($_POST['salary'] ?? '');
$description = trim($_POST['description'] ?? '');

// basic validation
$allowedCats = ['Software', 'Design', 'Marketing', 'Finance'];
if ($id <= 0 || $title === '' || $description === '' || $location === '' || !in_array($category, $allowedCats, true)) {
    flash('Please fill all fields correctly.', 'danger');
    header('Location: ' . url('edit_job.php') . '?id=' . $id);
    exit;
}

// normalize salary
if ($salary !== '') {
    $digits = preg_replace('/[^\d]/', '', $salary);
    if ($digits !== '')
        $salary = 'Rs. ' . number_format((int) $digits, 0, '.', ',');
}

// update with ownership check
$stmt = $mysqli->prepare(
    "UPDATE jobs
     SET title = ?, description = ?, category = ?, location = ?, salary = ?
   WHERE id = ? AND employer_id = ?"
);
$stmt->bind_param('sssssii', $title, $description, $category, $location, $salary, $id, $user['id']);
$stmt->execute();
$stmt->close();

flash('Job updated', 'success');
header('Location: ' . url('my_jobs.php'));
exit;
