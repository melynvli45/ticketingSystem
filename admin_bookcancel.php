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
    <nav class="navbar">
      <div class="logo">TixPop</div>

      <div class="nav-links">
        <a href="home.php">Home</a>
        <div class="dropdown">
          <button class="dropbtn">Manage Concert</button>
          <div class="dropdown-content">
            <a href="admin_ConcertAdd.php">Add Concert</a>
            <a href="admin_Concert.php">Concert List</a>
          </div>
        </div>
        <div class="dropdown">
          <button class="dropbtn">Booking List</button>
          <div class="dropdown-content">
            <a href="admin_bookpending.php">Pending</a>
            <a href="admin_bookapprove.php">  Approved</a>
            <a href="admin_bookcancel.php">Rejected</a>
          </div>
        </div>

        <div class="dropdown">
          <button class="dropbtn">Seat Category</button>
          <div class="dropdown-content">
            <a href="admin_SeatAdd.php">Add Category</a>
            <a href="admin_Seatcategory.php"> Seat List</a>
          </div>
        </div>

        <a href="admin_profile.php">Profile</a>
        <a href="index.php">Log Out</a>
      </div>
    </nav>

    <?php
    require_once __DIR__ . '/db.php';
    session_start();

    // require admin
    if (empty($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
        echo '<p style="color:#900">Access denied. Admins only.</p>';
    } else {
        $stmt = $pdo->prepare('SELECT p.Payment_ID, p.Invoice_ID, p.Payment_date, p.Payment_status, i.Quantity, i.Date AS invoice_date, u.Full_name, u.Email, e.Name AS event_name, c.Category_type, c.Price
                               FROM payment p
                               JOIN invoice i ON p.Invoice_ID = i.Invoice_ID
                               JOIN users u ON i.User_ID = u.User_ID
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               LEFT JOIN category c ON e.Category_ID = c.Category_ID
                               WHERE p.Payment_status = ?
                               ORDER BY p.Payment_date DESC');
        $stmt->execute(['rejected']);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            echo '<p>No rejected bookings.</p>';
        } else {
            echo '<table class="table"><thead><tr><th>No</th><th>Full Name</th><th>Concert</th><th>Seat Category</th><th>Quantity</th><th>Total</th><th>Status</th><th>Action</th></tr></thead><tbody>';
            $i = 1;
            foreach ($rows as $r) {
                $total = isset($r['Price']) ? number_format($r['Price'] * (int)$r['Quantity'], 2) : '0.00';
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($r['Full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($r['event_name'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Category_type'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($r['Quantity']) . '</td>';
                echo '<td>RM ' . $total . '</td>';
                echo '<td><span class="status cancelled">' . htmlspecialchars($r['Payment_status']) . '</span></td>';
                echo '<td><a href="delete_booking.php?invoice=' . (int)$r['Invoice_ID'] . '" class="delete-btn">Refund</a></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
    }
    ?>
  </body>
</html>
