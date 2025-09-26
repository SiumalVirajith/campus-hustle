<?php

require dirname(__DIR__, 2) . '/config/init.php';


$BASE = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
if ($BASE === '.' || $BASE === '\\')
    $BASE = '';

function back($msg)
{
    global $BASE;
    if (function_exists('flash') && $msg)
        flash($msg, 'danger');
    header('Location: ' . $BASE . '/applications.php');
    exit;
}

if (function_exists('csrf_verify'))
    csrf_verify();

$me = current_user();
if (!$me || ($me['role'] ?? '') !== 'employer')
    back('Unauthorized');

$appId = (int) ($_POST['app_id'] ?? 0);
$do = $_POST['do'] ?? '';
if ($appId <= 0 || !in_array($do, ['accept', 'reject'], true))
    back('Invalid request');


$stmt = $mysqli->prepare(
    "SELECT a.id, a.status
   FROM application a
   JOIN jobs j ON j.id = a.job_id
   WHERE a.id = ? AND j.employer_id = ?
   LIMIT 1"
);
$stmt->bind_param('ii', $appId, $me['id']);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app)
    back('Application not found');
if ($app['status'] !== 'pending')
    back('Already decided');

$new = ($do === 'accept') ? 'accepted' : 'rejected';

$upd = $mysqli->prepare("UPDATE application SET status = ?, decided_at = NOW() WHERE id = ?");
$upd->bind_param('si', $new, $appId);
$upd->execute();
$upd->close();

if (function_exists('flash')) {
    flash($new === 'accepted' ? 'Application accepted' : 'Application rejected', 'success');
}

header('Location: ' . $BASE . '/applications.php');
exit;
