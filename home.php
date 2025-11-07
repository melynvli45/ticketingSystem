<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Menu</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="home-page">
    <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
      <?php
        // For admin homepage we want Booking List to be a normal link (not a dropdown)
        $admin_simple_booking = true;
        include __DIR__ . '/admin_anavbar.php';
      ?>
    <?php else: ?>
    <nav class="navbar">
      <div class="logo">TixPop</div>
      <!-- 🎵 Audio Player -->
      <div class="audio-player">
        <button id="musicNote">🎵</button>
        <button id="muteBtn">🔇</button>
        <audio id="audio" src="image/Playlist.mp3" loop></audio>
      </div>
      <script src="script.js"></script>

      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="discover.php">Discover</a>
        <a href="ticketpurchase.php">Ticket Purchase</a>
        <a href="viewTicket.php">My Ticket</a>
  <a href="profile.php">Profile</a>
  <a href="logout.php">Log Out</a>
      </div>
    </nav>
    <?php endif; ?>

    <div class="title">
      <h1>WORLD BAND</h1>
      <h1>BIGGEST CONCERT</h1>
    </div>

    <div class="description">
      <p>
        Experience the ultimate celebration of live music at the World’s Biggest
        Band Concert at Axiata Arena Bukit Jalil, Malaysia! This extraordinary
        event brings together world-renowned bands, rising stars, and passionate
        fans from across the globe for one unforgettable night. With powerful
        performances, breathtaking stage effects, and an atmosphere charged with
        energy, this concert is more than just a show—it’s a once-in-a-lifetime
        experience. Don’t miss your chance to witness history in the
        making—secure your tickets today!
      </p>
    </div>

    <a href="discover.php">
      <button type="button" class="btn">Discover our concerts</button>
    </a>
  </body>
</html>
