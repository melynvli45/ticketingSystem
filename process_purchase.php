<?php
// process_purchase.php - handle ticket purchase: insert invoice and payment (pending)
require_once __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ticketpurchase.php');
    exit;
}

// Require login
if (empty($_SESSION['user_id'])) {
    // redirect to login
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
$category_type = trim($_POST['category_type'] ?? '');

if ($event_id <= 0) {
    $_SESSION['error'] = 'Please select a valid event.';
    header('Location: ticketpurchase.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO invoice (User_ID, Event_ID, Quantity) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $event_id, $quantity]);
    $invoice_id = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare('INSERT INTO payment (Invoice_ID, Payment_status) VALUES (?, ?)');
    $stmt2->execute([$invoice_id, 'pending']);

    $pdo->commit();

    header('Location: purchasesuccess.php?invoice=' . (int)$invoice_id);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('process_purchase error: ' . $e->getMessage());
    $_SESSION['error'] = 'There was an error processing your purchase. Please try again.';
    header('Location: ticketpurchase.php');
    exit;
}

