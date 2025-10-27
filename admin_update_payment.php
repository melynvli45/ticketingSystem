<?php
// admin_update_payment.php - approve or reject payments (admin only)
require_once __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_bookpending.php');
    exit;
}

$invoice_id = isset($_POST['invoice_id']) ? (int)$_POST['invoice_id'] : 0;
$action = $_POST['action'] ?? '';

if ($invoice_id <= 0 || !in_array($action, ['approve', 'reject'])) {
    header('Location: admin_bookpending.php');
    exit;
}

$status = $action === 'approve' ? 'approved' : 'rejected';

try {
    $stmt = $pdo->prepare('UPDATE payment SET Payment_status = ? WHERE Invoice_ID = ?');
    $stmt->execute([$status, $invoice_id]);
} catch (Exception $e) {
    error_log('admin_update_payment error: ' . $e->getMessage());
}

header('Location: admin_bookpending.php');
exit;
