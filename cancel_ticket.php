<?php
session_start();
require_once __DIR__ . '/db.php';

// Require login
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$invoice_id = (int)($_GET['invoice'] ?? $_POST['invoice_id'] ?? 0);
$user_id = (int)($_SESSION['user_id'] ?? 0);

if ($invoice_id === 0) {
    die('Invalid invoice ID.');
}

// Check if the ticket belongs to the user and is still 'active'
$stmt = $pdo->prepare('SELECT i.Invoice_ID, i.Ticket_status 
                       FROM invoice i 
                       WHERE i.Invoice_ID = ? AND i.User_ID = ? AND i.Ticket_status = "active"');
$stmt->execute([$invoice_id, $user_id]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die('Ticket not found, already cancelled, or you do not have permission to cancel this.');
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['cancel_reason'] ?? '');
    $other_details = trim($_POST['other_details'] ?? '');

    if (empty($reason)) {
        $error_message = 'Please select a reason for cancellation.';
    } else {
        // Construct the final reason string
        if ($reason === 'Other' && !empty($other_details)) {
             $final_reason = 'Other: ' . $other_details;
        } elseif ($reason === 'Other' && empty($other_details)) {
             $final_reason = 'Other (details not provided)';
        } else {
             $final_reason = $reason;
        }

        try {
            // Update the invoice to 'cancelled' and save the reason
            $stmt = $pdo->prepare('UPDATE invoice SET Ticket_status = "cancelled", Refund_reason = ? WHERE Invoice_ID = ? AND User_ID = ?');
            $stmt->execute([$final_reason, $invoice_id, $user_id]);
            
            // Redirect after successful cancellation
            header('Location: viewTicket.php?status=cancelled');
            exit;

        } catch (PDOException $e) {
            $error_message = 'An error occurred during cancellation. Please try again.';
            // Log error: error_log($e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cancel Ticket</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 { text-align: center; color: #dc3545; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 16px;
        }
        .submit-btn {
            display: block;
            width: 100%;
            background-color: #dc3545;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .submit-btn:hover { background-color: #c82333; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: #555; }
        .error { color: #dc3545; font-weight: bold; margin-bottom: 15px; text-align: center;}
    </style>
  </head>

  <body class="other-page">
    <div class="container">
        <h2>Cancel Ticket</h2>
        <p style="text-align: center;">Invoice ID: <strong><?= $invoice_id ?></strong></p>

        <?php if ($error_message): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">

            <div class="form-group">
                <label for="cancel_reason">Why do you want to cancel this ticket?</label>
                <select name="cancel_reason" id="cancel_reason" required>
                    <option value="">-- Select a reason --</option>
                    <option value="Event date/time conflict">Event date/time conflict</option>
                    <option value="Purchased the wrong ticket type/category">Purchased the wrong ticket type/category</option>
                    <option value="Found better seats elsewhere">Found better seats elsewhere</option>
                    <option value="Financial reasons/Change of plans">Financial reasons/Change of plans</option>
                    <option value="Other">Other (Please detail below)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="other_details">If 'Other' or for additional details, please specify (optional):</label>
                <textarea name="other_details" id="other_details" rows="3" placeholder="e.g., I accidentally booked 5 tickets instead of 2."></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Confirm Cancellation</button>
            </div>
        </form>
        
        <a href="viewTicket.php" class="back-link">← Go back to My Tickets</a>
    </div>
  </body>
</html>