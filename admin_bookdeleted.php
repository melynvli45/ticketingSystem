<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cancelled Bookings</title>
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
        // Fetch invoices where Ticket_status is 'cancelled'
        // UPDATED: LEFT JOIN payment table and select p.Proof_of_payment
        $stmt = $pdo->prepare('SELECT i.Invoice_ID, i.Quantity, i.Date AS invoice_date, i.Refund_reason, 
                               u.Full_name, u.Email, e.Name AS event_name, c.Category_type, c.Price,
                               p.Proof_of_payment 
                               FROM invoice i
                               JOIN users u ON i.User_ID = u.User_ID
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               LEFT JOIN category c ON i.Category_ID = c.Category_ID
                               LEFT JOIN payment p ON i.Invoice_ID = p.Invoice_ID /* Added JOIN to payment table */
                               WHERE i.Ticket_status = ?
                               ORDER BY i.Date DESC'); 
        $stmt->execute(['cancelled']);
        $rows = $stmt->fetchAll();

        echo '<h2>Cancelled Tickets List (User Requested)</h2>';

        if (!$rows) {
            echo '<p>No user-cancelled bookings found.</p>';
        } else {
            // UPDATED: Added 'Receipt' column header
            echo '<table class="table"><thead><tr><th>No</th><th>Full Name</th><th>Concert</th><th>Seat Category</th><th>Quantity</th><th>Total</th><th>Cancellation Reason</th><th>Receipt</th><th>Action</th></tr></thead><tbody>';
            $i = 1;
            foreach ($rows as $r) {
                // Calculate total
                $total = number_format(($r['Price'] ?? 0.00) * (int)($r['Quantity'] ?? 0), 2);

                // Logic to display a link to the receipt image
                $receipt_html = 'N/A';
                if (!empty($r['Proof_of_payment'])) {
                    $receipt_html = '<a href="' . htmlspecialchars($r['Proof_of_payment']) . '" target="_blank">View Receipt</a>';
                }
                
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($r['Full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($r['event_name'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Category_type'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Quantity']) . '</td>';
                echo '<td>RM ' . $total . '</td>';
                // Display the cancellation reason
                echo '<td>' . nl2br(htmlspecialchars($r['Refund_reason'] ?? 'Not provided')) . '</td>'; 
                // Display Receipt column
                echo '<td>' . $receipt_html . '</td>';
                // Admin action: can permanently delete the booking record (after processing any refund)
                echo '<td><a href="delete_booking.php?invoice=' . (int)$r['Invoice_ID'] . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to PERMANENTLY DELETE this cancelled booking?\')">Delete Record</a></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }
    ?>
  </body>
</html>