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
    <style>
      /* Ensure the buycontainer provides a height context if needed, but primarily target buyyes */
      .buycontainer {
        display: flex; /* Helps in centering and containing */
        justify-content: center;
        align-items: flex-start; /* Align to the top of the container */
        min-height: calc(100vh - 60px); /* Example: Take up most of the viewport height minus navbar */
        padding: 20px 0; /* Add some padding top/bottom */
      }

      /* Fix the 'white box' (.buyyes) to stretch down and fill content */
      /* Assuming it's the element containing all the success content */
      .buyyes {
        min-height: 700px; /* Increased min-height to stretch further down */
        width: 90%; /* Adjust width for better appearance */
        max-width: 600px; /* Max width for readability on large screens */
        padding: 30px; /* Add internal padding */
        box-sizing: border-box; /* Include padding in the element's total width and height */
        display: flex;
        flex-direction: column; /* Stack children vertically */
        align-items: center; /* Center content horizontally */
      }

      /* Style for the QR code image to make it big and center */
      .qr-image {
        max-width: 80%; /* Make it bigger, up to 80% of its container */
        width: 300px; /* Set a specific target size for better visibility */
        height: auto;
        display: block !important; /* Override inline style if necessary */
        margin: 12px auto !important; /* Center the block element */
      }
    </style>
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
        <a href="logout.php">Log Out</a>
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
        <div class="buyheader">
          <span class="buytitle">THANK YOU!</span>
        </div>

        <div class="description2">
          <?php if ($invoice): ?>
            <p>Congratulations — your purchase was recorded.<br />Invoice ID: <strong><?=htmlspecialchars($invoice['Invoice_ID'])?></strong><br />Event: <?=htmlspecialchars($invoice['event_name'] ?? 'N/A')?>
            <br />Quantity: <?=htmlspecialchars($invoice['Quantity'])?>
            <br />Payment status: <?=htmlspecialchars($payment['Payment_status'] ?? 'pending')?></p>

            <div style="margin-top:16px; width: 100%; text-align: center;"> <h3>Pay using QR</h3>
              <?php
                // prefer image/qr.jpg per provided path, fallback to other common locations
                if (file_exists(__DIR__ . '/image/qr.jpg')): ?>
                <img src="image/qr.jpg" alt="QR Payment" class="qr-image" />
              <?php elseif (file_exists(__DIR__ . '/image/qr.png')): ?>
                <img src="image/qr.png" alt="QR Payment" class="qr-image" />
              <?php elseif (file_exists(__DIR__ . '/img/qr.jpg')): ?>
                <img src="img/qr.jpg" alt="QR Payment" class="qr-image" />
              <?php elseif (file_exists(__DIR__ . '/img/qr.png')): ?>
                <img src="img/qr.png" alt="QR Payment" class="qr-image" />
              <?php else: ?>
                <p><em>QR image not found. Please contact support.</em></p>
              <?php endif; ?>

              <?php if (!empty($payment['Proof_of_payment'])): ?>
                <p>Receipt uploaded: <a href="<?=htmlspecialchars($payment['Proof_of_payment'])?>" target="_blank">View receipt</a></p>
              <?php else: ?>
                <form method="post" action="upload_receipt.php" enctype="multipart/form-data">
                  <input type="hidden" name="invoice_id" value="<?= (int)$invoice['Invoice_ID'] ?>" />
                  <label for="receipt">Upload receipt (JPG/PNG/PDF):</label><br />
                  <input type="file" name="receipt" id="receipt" accept="image/*,application/pdf" required />
                  <div style="margin-top:10px">
                    <button type="submit" class="btn-tix">Upload Receipt and Complete Payment</button>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <p>Thank you. We couldn't find the invoice details but your purchase may still have been recorded.</p>
          <?php endif; ?>

          <div class="btn-container" style="margin-top:14px">
            <a href="home.php">
              <button type="button" class="btn-tix">Go back to home</button>
            </a>
            <a href="viewTicket.php">
              <button type="button" class="btn-tix">View Ticket</button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>