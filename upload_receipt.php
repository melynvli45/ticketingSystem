<?php
// upload_receipt.php - handle user uploading proof of payment
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ticketpurchase.php');
    exit;
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$invoice_id = isset($_POST['invoice_id']) ? (int)$_POST['invoice_id'] : 0;
if ($invoice_id <= 0) {
    $_SESSION['error'] = 'Invalid invoice.';
    header('Location: viewTicket.php');
    exit;
}

// check invoice belongs to user (unless admin)
$stmt = $pdo->prepare('SELECT i.User_ID, p.Payment_ID FROM invoice i JOIN payment p ON p.Invoice_ID = i.Invoice_ID WHERE i.Invoice_ID = ?');
$stmt->execute([$invoice_id]);
$row = $stmt->fetch();
if (!$row) {
    $_SESSION['error'] = 'Invoice not found.';
    header('Location: viewTicket.php');
    exit;
}

if ($_SESSION['user_type'] !== 'admin' && (int)$row['User_ID'] !== (int)$_SESSION['user_id']) {
    $_SESSION['error'] = 'You are not authorized to upload for this invoice.';
    header('Location: viewTicket.php');
    exit;
}

if (empty($_FILES['receipt']) || !isset($_FILES['receipt']['error'])) {
    $_SESSION['error'] = 'No file uploaded.';
    header('Location: purchasesuccess.php?invoice=' . $invoice_id);
    exit;
}

if ($_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Upload failed (code ' . (int)$_FILES['receipt']['error'] .').' ;
    header('Location: purchasesuccess.php?invoice=' . $invoice_id);
    exit;
}

$tmp = $_FILES['receipt']['tmp_name'];
$orig = $_FILES['receipt']['name'];
$ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png','pdf'];
if (!in_array($ext, $allowed)) {
    $_SESSION['error'] = 'Invalid file type. Use JPG, PNG or PDF.';
    header('Location: purchasesuccess.php?invoice=' . $invoice_id);
    exit;
}

$safeBase = preg_replace('/[^a-z0-9-_\.]/i', '-', pathinfo($orig, PATHINFO_FILENAME));
$targetDir = __DIR__ . '/uploads';
if (!is_dir($targetDir)) @mkdir($targetDir, 0755, true);
$target = $targetDir . '/payment_' . $invoice_id . '_' . time() . '_' . $safeBase . '.' . $ext;
if (!move_uploaded_file($tmp, $target)) {
    $_SESSION['error'] = 'Unable to save uploaded file.';
    header('Location: purchasesuccess.php?invoice=' . $invoice_id);
    exit;
}

// store relative path
$relPath = 'uploads/' . basename($target);

try {
    $up = $pdo->prepare('UPDATE payment SET Proof_of_payment = ?, Payment_date = NOW(), Payment_status = ? WHERE Invoice_ID = ?');
    // keep status as 'pending' or set to 'waiting' for admin review
    $up->execute([$relPath, 'pending', $invoice_id]);
    $_SESSION['success'] = 'Receipt uploaded. Please wait for admin verification.';
} catch (Exception $e) {
    error_log('upload_receipt error: ' . $e->getMessage());
    $_SESSION['error'] = 'Unable to save receipt information.';
    // attempt to remove file
    @unlink($target);
}

// redirect user to view tickets
header('Location: viewTicket.php');
exit;

?>
