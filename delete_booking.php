<?php
// delete_booking.php - allow owner or admin to delete/cancel an invoice (and its payment)
require_once __DIR__ . '/db.php';
session_start();

// must be logged in
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$invoice_id = isset($_GET['invoice']) ? (int)$_GET['invoice'] : 0;
if ($invoice_id <= 0) {
    header('Location: viewTicket.php');
    exit;
}

// fetch invoice to check ownership
$stmt = $pdo->prepare('SELECT Invoice_ID, User_ID FROM invoice WHERE Invoice_ID = ?');
$stmt->execute([$invoice_id]);
$inv = $stmt->fetch();
if (!$inv) {
    // not found
    header('Location: viewTicket.php');
    exit;
}

$current_user = (int)$_SESSION['user_id'];
$is_admin = (($_SESSION['user_type'] ?? '') === 'admin');

if (!$is_admin && $inv['User_ID'] != $current_user) {
    // not owner
    header('HTTP/1.1 403 Forbidden');
    echo 'Forbidden';
    exit;
}

try {
    $pdo->beginTransaction();

    // delete payment (if exists) and invoice (payment has FK ON DELETE CASCADE but we'll explicitly delete payment first to be safe)
    $d1 = $pdo->prepare('DELETE FROM payment WHERE Invoice_ID = ?');
    $d1->execute([$invoice_id]);

    $d2 = $pdo->prepare('DELETE FROM invoice WHERE Invoice_ID = ?');
    $d2->execute([$invoice_id]);

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('delete_booking error: ' . $e->getMessage());
    // redirect back
    if ($is_admin) header('Location: admin_bookpending.php');
    else header('Location: viewTicket.php');
    exit;
}

// Redirect back
if ($is_admin) header('Location: admin_bookpending.php');
else header('Location: viewTicket.php');
exit;
