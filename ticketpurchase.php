<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ticket Purchase</title>
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

    <?php
    // ticketpurchase.php - show available events from DB and post to process_purchase.php
    require_once __DIR__ . '/db.php';
    session_start();

    // fetch events to populate select
    try {
        $eventsStmt = $pdo->query('SELECT Event_ID, Name, Date, Time, Venue FROM event ORDER BY Date ASC');
        $events = $eventsStmt->fetchAll();
    } catch (Exception $e) {
        $events = [];
    }
    ?>

    <div class="ticket-buy-box">
      <form method="post" action="process_purchase.php">
        <h1>PLEASE ENTER YOUR DETAILS</h1>

        <?php if (empty($_SESSION['user_id'])): ?>
          <p style="color:#900">You must <a href="login.php">log in</a> to purchase tickets.</p>
        <?php endif; ?>

        <label>SELECT CONCERT: </label>
        <select name="event_id" required>
          <option value="">--SELECT ONE--</option>
          <?php foreach ($events as $ev): ?>
            <option value="<?=htmlspecialchars($ev['Event_ID'])?>"><?=htmlspecialchars($ev['Name'].' — '.date('Y-m-d', strtotime($ev['Date'])).' @ '.$ev['Time'])?></option>
          <?php endforeach; ?>
        </select>

        <label>SEAT CATEGORY: </label>
        <select name="category_type" required>
          <option value="">--SELECT ONE--</option>
          <option value="BRONZE">BRONZE - RM 599.00</option>
          <option value="PLATINUM">PLATINUM - RM 699.00</option>
          <option value="GOLD">GOLD - RM 899.00</option>
          <option value="VIP">VIP - RM 1099.00</option>
        </select>

        <label>QUANTITY</label>
        <input type="number" name="quantity" value="1" min="1" required />

        <label>PAYMENT METHOD: </label>
        <div class="radio-group">
          <label>
            <input type="radio" name="payment_method" value="online" required />
            ONLINE BANKING
          </label>
          <label>
            <input type="radio" name="payment_method" value="duitnow" required />
            DUITNOW
          </label>
        </div>

        <label>
          <input type="checkbox" required />
          I AGREE WITH TERM AND CONDITION.
        </label>

        <div class="btn-container">
          <button class="btn-tix" type="submit">SUBMIT</button>
          <button class="btn-tix" type="reset">CANCEL</button>
        </div>
      </form>
    </div>
  </body>
</html>
