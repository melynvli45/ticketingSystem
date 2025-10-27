<?php
session_start();
require_once __DIR__ . '/db.php';

// Require login
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int)($_SESSION['user_id'] ?? 0);
$is_admin = (($_SESSION['user_type'] ?? '') === 'admin');

// Fetch tickets: admins see all, regular users see only their own
if ($is_admin) {
  $stmt = $pdo->prepare(
    "SELECT i.Invoice_ID, i.Quantity, i.Date AS InvoiceDate,
        p.Payment_status, e.Name AS EventName, c.Category_type, c.Price, u.Full_name, u.User_ID
     FROM invoice i
     LEFT JOIN payment p ON p.Invoice_ID = i.Invoice_ID
     LEFT JOIN event e ON e.Event_ID = i.Event_ID
     LEFT JOIN category c ON c.Category_ID = e.Category_ID
     LEFT JOIN users u ON i.User_ID = u.User_ID
     ORDER BY i.Date DESC"
  );
  $stmt->execute();
} else {
  $stmt = $pdo->prepare(
    "SELECT i.Invoice_ID, i.Quantity, i.Date AS InvoiceDate,
        p.Payment_status, e.Name AS EventName, c.Category_type, c.Price
     FROM invoice i
     LEFT JOIN payment p ON p.Invoice_ID = i.Invoice_ID
     LEFT JOIN event e ON e.Event_ID = i.Event_ID
     LEFT JOIN category c ON c.Category_ID = e.Category_ID
     WHERE i.User_ID = ?
     ORDER BY i.Date DESC"
  );
  $stmt->execute([$user_id]);
}
$tickets = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My ticket</title>
    <link rel="stylesheet" href="style.css" />
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
        <a href="discover.php">Discover</a>
        <a href="seatCategory.php">Seat Category</a>
        <a href="ticketpurchase.php">Ticket Purchase</a>
        <a href="viewTicket.php">My Ticket</a>
        <a href="profile.php">Profile</a>
        <a href="index.php">Log Out</a>
      </div>
    </nav>

    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Full Name</th>
          <th>Concert</th>
          <th>Seat Category</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Invoice ID</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>

        <tbody>
          <?php if (empty($tickets)): ?>
            <tr>
              <td colspan="9">No tickets found. <a href="ticketpurchase.php">Buy tickets</a></td>
            </tr>
          <?php else: ?>
            <?php $no = 1; foreach ($tickets as $t): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($t['Full_name'] ?? ($_SESSION['full_name'] ?? '')) ?></td>
                <td><?= htmlspecialchars($t['EventName'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($t['Category_type'] ?? 'N/A') ?></td>
                <td><?= (int)($t['Quantity'] ?? 0) ?></td>
                <td>
                  <?php if (!empty($t['Price'])): ?>
                    <?= 'RM ' . number_format($t['Price'] * (int)$t['Quantity'], 2) ?>
                  <?php else: ?>
                    RM 0.00
                  <?php endif; ?>
                </td>
                <td>
                  <a href="invoice.php?invoice=<?= (int)$t['Invoice_ID'] ?>"><?= (int)$t['Invoice_ID'] ?></a>
                </td>
                <td>
                  <span class="status <?= htmlspecialchars($t['Payment_status'] ?? 'pending') ?>">
                    <?= htmlspecialchars(ucfirst($t['Payment_status'] ?? 'pending')) ?>
                  </span>
                </td>
                <td>
                  <?php if (($t['Payment_status'] ?? 'pending') === 'pending'): ?>
                    <?php if ($is_admin): ?>
                      <form method="post" action="admin_update_payment.php" style="display:inline"> 
                        <input type="hidden" name="invoice_id" value="<?= (int)$t['Invoice_ID'] ?>"> 
                        <input type="hidden" name="action" value="approve"> 
                        <button type="submit" class="update-btn">Approve</button>
                      </form>
                      <form method="post" action="admin_update_payment.php" style="display:inline;margin-left:6px"> 
                        <input type="hidden" name="invoice_id" value="<?= (int)$t['Invoice_ID'] ?>"> 
                        <input type="hidden" name="action" value="reject"> 
                        <button type="submit" class="delete-btn">Reject</button>
                      </form>
                      <!-- Admin may also delete if desired -->
                      <a href="delete_booking.php?invoice=<?= (int)$t['Invoice_ID'] ?>" class="delete-btn" style="margin-left:6px">Delete</a>
                    <?php else: ?>
                      <a href="delete_booking.php?invoice=<?= (int)$t['Invoice_ID'] ?>" class="delete-btn">Delete</a>
                    <?php endif; ?>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
    </table>
  </body>
</html>
