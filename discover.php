<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="widTH=device-widTH, initial-scale=1.0" />
    <title>Discover</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="home-page">
    <nav class="navbar">
      <div class="logo">TixPop</div>

      <div class="audio-player">
        <button id="musicNote">🎵</button>
        <button id="muteBtn">🔇</button>
        <audio id="audio" src="image/Playlist.mp3" loop></audio>
      </div>
      <script src="script.js"></script>

      <div class="nav-links">
        <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
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
              <a href="admin_bookapprove.php">Approved</a>
              <a href="admin_bookcancel.php">Rejected</a>
            </div>
          </div>
          <a href="admin_Seatcategory.php">Seat Category</a>
          <a href="admin_profile.php">Profile</a>
          <a href="index.php">Log Out</a>
        <?php else: ?>
          <a href="home.php">Home</a>
          <a href="discover.php">Discover</a>
          <a href="ticketpurchase.php">Ticket Purchase</a>
          <a href="viewTicket.php">My Ticket</a>
          <a href="profile.php">Profile</a>
          <a href="logout.php">Log Out</a>
        <?php endif; ?>
      </div>

      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search concerts" />
        <button class="search-btn"><i class='bx bx-search'></i></button>
      </div>
    </nav>

    <div class="title">
      <h1>CONCERTS FOR YOU</h1>
      <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
        <div style="margin-top:12px"><a class="eventbtn" href="admin_ConcertAdd.php">+ Add New Event</a></div>
      <?php endif; ?>
    </div>

    <div class="band-box">
      <?php
      // load events from DB
      require_once __DIR__ . '/db.php';
      try {
        // include poster column if present in the table
        $stmt = $pdo->query('SELECT Event_ID, Name, Date, Time, Venue, poster FROM event ORDER BY Date ASC');
        $events = $stmt->fetchAll();
      } catch (Exception $e) {
        $events = [];
      }

      function posterForName($name) {
        $n = strtolower($name);
        if (stripos($n, 'black') !== false || stripos($n, 'blackpink') !== false) return 'image/blackpink.png';
        if (stripos($n, 'stray') !== false || stripos($n, 'stray kids') !== false) return 'image/straykids.jpg';
        if (stripos($n, 'new') !== false || stripos($n, 'newjeans') !== false) return 'image/newjeans.png';
        if (stripos($n, 'enha') !== false || stripos($n, 'enha') !== false) return 'image/enha2.jpg';
        // fallback poster
        return 'image/tick1.jpg';
      }

      if (empty($events)) {
        echo '<p>No upcoming concerts found.</p>';
      } else {
        foreach ($events as $ev) {
          $poster = null;
          if (!empty($ev['poster'])) {
            $path = __DIR__ . '/' . $ev['poster'];
            if (file_exists($path)) {
              $poster = $ev['poster'];
            }
          }
          if ($poster === null) $poster = posterForName($ev['Name']);
          $displayDate = htmlspecialchars($ev['Date']);
          $displayTime = htmlspecialchars($ev['Time'] ?? 'TBA');
          $venue = htmlspecialchars($ev['Venue'] ?? 'TBA');
          $name = htmlspecialchars($ev['Name'] ?? 'Unnamed Event');
          $eid = (int)$ev['Event_ID'];
          echo '<div class="band-card">';
          echo '<div class="img-wrapper"><img src="' . htmlspecialchars($poster) . '" alt="' . $name . '" /></div>';
          echo '<h3>' . $name . '</h3>';
          echo '<p>EVENT ID: ' . $eid . '<br>DATE: ' . $displayDate . '<br>START TIME: ' . $displayTime . '<br>VENUE: ' . $venue . '</p>';
          if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
            echo '<div style="margin-top:8px">';
            echo '<a class="eventbtn" href="admin_ConcertAdd.php?id=' . $eid . '">Edit</a>';
            echo '</div>';
          }
          echo '</div>';
        }
      }
      ?>

    </div>
  </body>
</html>
