<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice</title>
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

    <div class="ticket-buy-box">
        <h1>INVOICE</h1>

        <label>INVOICE ID: </label>
        <input type="text" placeholder="6216562" readonly />

        <label>USERNAME: </label>
        <input
          type="text"
          placeholder="USERNAME"
          required readonly
        />

        <label>EVENT ID: </label>
        <input type="text" placeholder="IDIDID" readonly />

        <label>PAYMENT ID: </label>
        <a href="paymentProof.php">PAYMENT_939339</a>

        <div class="btn-container">
          <button class="btn-tix" onclick="window.location.href='home.php'">GO TO HOME</button>
        </div>
      </form>
    </div>
  </body>
</html>
