<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pending Bookings</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <?php
    require_once __DIR__ . '/db.php';
    session_start();

    // require admin
    if (empty($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
        echo '<p style="color:#900">Access denied. Admins only.</p>';
    } else {
        // Fetch invoices where Payment_status is 'pending' AND Ticket_status is NOT 'cancelled'
        $stmt = $pdo->prepare('SELECT p.Payment_ID, p.Invoice_ID, p.Payment_date, p.Payment_status, i.Quantity, i.Date AS invoice_date, u.Full_name, u.Email, e.Name AS event_name
                               FROM payment p
                               JOIN invoice i ON p.Invoice_ID = i.Invoice_ID
                               JOIN users u ON i.User_ID = u.User_ID
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               WHERE p.Payment_status = ? 
                               AND i.Ticket_status != "cancelled" /* FIX: Exclude user-cancelled tickets */
                               ORDER BY p.Payment_date DESC');
        $stmt->execute(['pending']);
        $rows = $stmt->fetchAll();

        echo '<h2>Pending Bookings List</h2>';

        if (!$rows) {
            echo '<p>No pending bookings found.</p>';
        } else {
            echo '<table class="table"><thead><tr><th>No</th><th>Full Name</th><th>Concert</th><th>Quantity</th><th>Status</th><th>Action</th></tr></thead><tbody>';
            $i = 1;
            foreach ($rows as $r) {
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($r['Full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($r['event_name'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Quantity']) . '</td>';
                echo '<td><span class="status pending">' . htmlspecialchars($r['Payment_status']) . '</span></td>';
                echo '<td>
                    <form method="post" action="admin_update_payment.php" style="display:inline"> 
                        <input type="hidden" name="invoice_id" value="' . (int)$r['Invoice_ID'] . '"> 
                        <input type="hidden" name="action" value="approve"> 
                        <button type="submit" class="update-btn">Approve</button>
                    </form>
                    <form method="post" action="admin_update_payment.php" style="display:inline;margin-left:6px"> 
                        <input type="hidden" name="invoice_id" value="' . (int)$r['Invoice_ID'] . '"> 
                        <input type="hidden" name="action" value="reject"> 
                        <button type="submit" class="delete-btn">Reject</button>
                    </form>
                </td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }
    ?>
  </body>
</html>