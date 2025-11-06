<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Proof</title>
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
  <a href="logout.php">Log Out</a>
      </div>
    </nav>

    <div class="payment">
        <h1>PAYMENT</h1>

      <label>PAYMENT ID:</label>
      <a href="paymentProof.php" class="proof-link">PAYMENT_939339</a>

      <label>INVOICE ID:</label>
      <span class="readonly-text">6216562</span>

      <label>PAYMENT DATE:</label>
      <span class="readonly-text">21 October 2025</span>

      <label>PROOF OF PAYMENT (photo):</label>
      <a href="uploads/payment939339.jpg" target="_blank" class="proof-link">View Payment Proof</a>

      <label>PAYMENT STATUS:</label>
      <span class="pstatus approved">Approved</span>

      <div class="btn-container">
        <button class="btn-tix" onclick="window.location.href='home.php'">GO TO HOME</button>
      </div>
    </div>
  </body>
</html>
