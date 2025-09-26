<?php
require dirname(__DIR__, 2) . '/config/init.php';

function back($msg)
{
  header('Location: ' . url('register.php') . '?err=' . urlencode($msg));
  exit;
}

if (function_exists('csrf_verify'))
  csrf_verify();

$role = $_POST['role'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
$pass2 = $_POST['password2'] ?? '';

if (!in_array($role, ['student', 'employer'], true))
  back('Invalid role');
if ($name === '')
  back('Name is required');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  back('Invalid email');
if (strlen($pass) < 6)
  back('Password must be at least 6 characters');
if ($pass !== $pass2)
  back('Passwords do not match');

$hash = password_hash($pass, PASSWORD_BCRYPT);

try {
  $stmt = $mysqli->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
  $stmt->bind_param('ssss', $name, $email, $hash, $role);
  $stmt->execute();
  $uid = $stmt->insert_id;
  $stmt->close();
} catch (mysqli_sql_exception $e) {
  if ($e->getCode() == 1062)
    back('Email already registered');
  back('Database error: ' . $e->getMessage());
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $uid;

header('Location: ' . url('index.php'));
exit;
