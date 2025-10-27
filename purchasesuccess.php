<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Successful</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="home-page">
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

    <?php
    // purchasesuccess.php - show basic invoice/payment details after purchase
    require_once __DIR__ . '/db.php';
    $invoice_id = isset($_GET['invoice']) ? (int)$_GET['invoice'] : 0;
    $invoice = null;
    $payment = null;
    if ($invoice_id > 0) {
        $stmt = $pdo->prepare('SELECT i.Invoice_ID, i.Quantity, i.Date, u.Full_name, u.Email, e.Name AS event_name
                               FROM invoice i
                               JOIN users u ON i.User_ID = u.User_ID
                               LEFT JOIN event e ON i.Event_ID = e.Event_ID
                               WHERE i.Invoice_ID = ?');
        $stmt->execute([$invoice_id]);
        $invoice = $stmt->fetch();

        $pstmt = $pdo->prepare('SELECT * FROM payment WHERE Invoice_ID = ?');
        $pstmt->execute([$invoice_id]);
        $payment = $pstmt->fetch();
    }
    ?>

    <div class="buycontainer">
      <div class="buyyes">
        <img src="image/tick1.jpg" />
        <div class="buyheader">
          <span class="buytitle">THANK YOU!</span>
        </div>

        <div class="description2">
          <?php if ($invoice): ?>
            <p>Congratulations — your purchase was recorded.<br />Invoice ID: <strong><?=htmlspecialchars($invoice['Invoice_ID'])?></strong><br />Event: <?=htmlspecialchars($invoice['event_name'] ?? 'N/A')?>
            <br />Quantity: <?=htmlspecialchars($invoice['Quantity'])?>
            <br />Payment status: <?=htmlspecialchars($payment['Payment_status'] ?? 'pending')?></p>
          <?php else: ?>
            <p>Thank you. We couldn't find the invoice details but your purchase may still have been recorded.</p>
          <?php endif; ?>
        </div>

        <div class="btn-container">
          <a href="home.php">
            <button type="button" class="btn-tix">Go back to home</button>
          </a>
          <a href="viewTicket.php">
            <button type="button" class="btn-tix">View Ticket</button>
          </a>
        </div>
      </div>
    </div>
  </body>
</html>
