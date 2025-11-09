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
    require_once __DIR__ . '/db.php';
    session_start();

    // require admin
    if (empty($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
        echo '<p style="color:#900">Access denied. Admins only.</p>';
    } else {
        // UPDATED: Added p.Proof_of_payment
        $stmt = $pdo->prepare('SELECT 
                                p.Payment_ID, 
                                p.Invoice_ID, 
                                p.Payment_date, 
                                p.Payment_status, 
                                p.Proof_of_payment,
                                i.Quantity, 
                                i.Date AS invoice_date, 
                                u.Full_name, 
                                u.Email, 
                                e.Name AS event_name, 
                                c.Category_type, 
                                c.Price
                               FROM payment p
                               LEFT JOIN invoice i ON p.Invoice_ID = i.Invoice_ID 
                               LEFT JOIN users u ON i.User_ID = u.User_ID           
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               LEFT JOIN category c ON i.Category_ID = c.Category_ID 
                               WHERE p.Payment_status = ?
                               ORDER BY p.Payment_date DESC');
        $stmt->execute(['rejected']);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            echo '<p>No rejected bookings.</p>';
        } else {
            // UPDATED: Added 'Receipt' column header
            echo '<table class="table"><thead><tr><th>No</th><th>Full Name</th><th>Concert</th><th>Seat Category</th><th>Quantity</th><th>Total</th><th>Status</th><th>Receipt</th><th>Action</th></tr></thead><tbody>';
            $i = 1;
            foreach ($rows as $r) {
                // Use the null coalescing operator (??) for safety if fields from LEFT JOINs are missing
                $quantity = (int)($r['Quantity'] ?? 0);
                $price = $r['Price'] ?? 0.00;
                $total = number_format($price * $quantity, 2);
                
                // Logic to display a link to the receipt image
                $receipt_html = 'N/A';
                if (!empty($r['Proof_of_payment'])) {
                    $receipt_html = '<a href="' . htmlspecialchars($r['Proof_of_payment']) . '" target="_blank">View Receipt</a>';
                }
                
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($r['Full_name'] ?? 'N/A User') . '</td>';
                echo '<td>' . htmlspecialchars($r['event_name'] ?? 'N/A Event') . '</td>';
                echo '<td>' . htmlspecialchars($r['Category_type'] ?? 'N/A Category') . '</td>';
                echo '<td>' . htmlspecialchars($quantity) . '</td>';
                echo '<td>RM ' . $total . '</td>';
                echo '<td><span class="status cancelled">' . htmlspecialchars($r['Payment_status']) . '</span></td>';
                // Display Receipt column
                echo '<td>' . $receipt_html . '</td>';
                echo '<td><a href="delete_booking.php?invoice=' . (int)$r['Invoice_ID'] . '" class="delete-btn">Refund</a></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }
    ?>
    </body>
</html>