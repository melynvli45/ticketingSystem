<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Menu</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <?php
    // Admin pending bookings: list payments with status 'pending'
    require_once __DIR__ . '/db.php';
    session_start();

    // require admin
    if (empty($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
        echo '<p style="color:#900">Access denied. Admins only.</p>';
    } else {
  $stmt = $pdo->prepare('SELECT p.Payment_ID, p.Invoice_ID, p.Payment_date, p.Proof_of_payment, p.Payment_status, i.Quantity, i.Date AS invoice_date, u.Full_name, u.Email, e.Name AS event_name
                               FROM payment p
                               JOIN invoice i ON p.Invoice_ID = i.Invoice_ID
                               JOIN users u ON i.User_ID = u.User_ID
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               WHERE p.Payment_status = ?
                               ORDER BY p.Payment_date ASC');
        $stmt->execute(['pending']);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            echo '<p>No pending bookings.</p>';
        } else {
            echo '<table class="table"><thead><tr><th>No</th><th>Full Name</th><th>Concert</th><th>Quantity</th><th>Invoice ID</th><th>Receipt</th><th>Payment Date</th><th>Status</th><th>Action</th></tr></thead><tbody>';
            $i = 1;
            foreach ($rows as $r) {
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($r['Full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($r['event_name'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Quantity']) . '</td>';
                echo '<td><a href="invoice.php?invoice=' . (int)$r['Invoice_ID'] . '">' . (int)$r['Invoice_ID'] . '</a></td>';
                echo '<td>' . (!empty($r['Proof_of_payment']) ? '<a href="' . htmlspecialchars($r['Proof_of_payment']) . '" target="_blank">View</a>' : 'No receipt') . '</td>';
                echo '<td>' . htmlspecialchars($r['Payment_date']) . '</td>';
                echo '<td><span class="status pending">' . htmlspecialchars($r['Payment_status']) . '</span></td>';
                echo '<td>';
                // approve form
                echo '<form method="post" action="admin_update_payment.php" style="display:inline">';
                echo '<input type="hidden" name="invoice_id" value="' . (int)$r['Invoice_ID'] . '">';
                echo '<input type="hidden" name="action" value="approve">';
                echo '<button type="submit" class="update-btn">Approve</button>';
                echo '</form> ';
                // reject form
                echo '<form method="post" action="admin_update_payment.php" style="display:inline;margin-left:6px">';
                echo '<input type="hidden" name="invoice_id" value="' . (int)$r['Invoice_ID'] . '">';
                echo '<input type="hidden" name="action" value="reject">';
                echo '<button type="submit" class="delete-btn">Reject</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }
    ?>
  </body>
</html>
