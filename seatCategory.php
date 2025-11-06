<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seat Categories</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="home-page">
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
        <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
          <a href="admin_SeatAdd.php">Add Category</a>
        <?php endif; ?>
      </div>
    </nav>

    <div class="title">
      <h1>SEAT CATEGORY</h1>
    </div>

    <div class="category-box">
      <?php
      try {
        $cats = $pdo->query('SELECT Category_ID, Category_type, Price, description FROM category ORDER BY Price DESC')->fetchAll();
      } catch (Exception $e) {
        $cats = [];
      }

      if (empty($cats)) {
        echo '<p>No categories available.</p>';
      } else {
        foreach ($cats as $c) {
          echo '<div class="category-card">';
          echo '<h3>' . htmlspecialchars($c['Category_type']) . '</h3>';
          echo '<p>Category ID: ' . (int)$c['Category_ID'] . '<br />PRICE: RM ' . number_format($c['Price'],2) . '<br />' . htmlspecialchars($c['description'] ?? '') . '</p>';
          echo '</div>';
        }
      }
      ?>
    </div>
  </body>
</html>
