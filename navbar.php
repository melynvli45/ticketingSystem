<?php
// navbar.php - reusable navigation include for the user pages
// Starts session (if not already started) and renders different links for
// authenticated users vs guests.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
  <div class="logo">TixPop</div>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="discover.php">Discover</a>

    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="seatCategory.php">Seat Category</a>
      <a href="ticketpurchase.php">Ticket Purchase</a>
      <a href="viewTicket.php">My Ticket</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Log Out</a>
    <?php else: ?>
      <a href="seatCategory.php">Seat Category</a>
      <a href="login.php">Log In</a>
      <a href="register.php">Register</a>
    <?php endif; ?>

    <div class="audio-player">
      <button id="musicNote">🎵</button>
      <button id="muteBtn">🔇</button>
      <audio id="audio" src="image/Playlist.mp3" loop></audio>
    </div>
  </div>
</nav>

<!-- 🎧 Audio player script -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const audio = document.getElementById("audio");
    const musicNote = document.getElementById("musicNote");
    const muteBtn = document.getElementById("muteBtn");

    if (audio && musicNote && muteBtn) {
      // Play / pause toggle
      musicNote.addEventListener("click", () => {
        if (audio.paused) {
          audio.play();
          musicNote.textContent = "🎶";
        } else {
          audio.pause();
          musicNote.textContent = "🎵";
        }
      });

      // Mute / unmute toggle
      muteBtn.addEventListener("click", () => {
        audio.muted = !audio.muted;
        muteBtn.textContent = audio.muted ? "🔇" : "🔊";
      });
    }
  });
</script>
