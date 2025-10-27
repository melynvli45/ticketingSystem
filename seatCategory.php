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
        <a href="seatCategory.php">Seat Category</a>
        <a href="ticketpurchase.php">Ticket Purchase</a>
        <a href="viewTicket.php">My Ticket</a>
        <a href="profile.php">Profile</a>
        <a href="index.php">Log Out</a>
      </div>
    </nav>

    <div class="title">
      <h1>SEAT CATEGORY</h1>
    </div>

    <!--Again, bayangan, delete masa buat php, nak tukar dipersilakan-->

    <div class="category-box">
      <div class="category-card">
        <h3>[EVENT ID 1]</h3>
        <p>
          Category ID: IDID<br />
          PRICE: RM 899.00<br />
          CATEGORY TYPE: VVVVVVIP
        </p>
      </div>

      <div class="category-card">
        <h3>[EVENT ID 2]</h3>
        <p>
          Category ID: IDID2<br />
          PRICE: RM 5009.00<br />
          CATEGORY TYPE: GALAXY
        </p>
      </div>
    </div>
  </body>
</html>
