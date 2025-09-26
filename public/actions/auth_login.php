<?php
require dirname(__DIR__, 2) . '/config/init.php';

$BASE = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
if ($BASE === '.' || $BASE === '\\')
    $BASE = '';

function back($msg)
{
    global $BASE;
    header('Location: ' . $BASE . '/login.php?err=' . urlencode($msg));
    exit;
}

csrf_verify();

$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '')
    back('Invalid email or password');

$stmt = $mysqli->prepare('SELECT id, password FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || !password_verify($pass, $row['password']))
    back('Incorrect email or password');

// Success: set session
session_regenerate_id(true);
$_SESSION['user_id'] = (int) $row['id'];

header('Location: ' . $BASE . '/index.php');
exit;
