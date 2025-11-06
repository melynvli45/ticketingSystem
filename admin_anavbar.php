<nav class="navbar">
  <div class="logo">TixPop</div>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <!-- Manage Concert: single link to concert list (click shows admin_Concert.php) -->
    <a href="admin_Concert.php">Manage Concert</a>
    <div class="dropdown">
      <button class="dropbtn">Booking List</button>
      <div class="dropdown-content">
        <a href="admin_bookpending.php">Pending</a>
        <a href="admin_bookapprove.php"> Approved</a>
        <a href="admin_bookcancel.php">Rejected</a>
      </div>
    </div>

    <!-- Seat Category: direct link to seat list -->
    <a href="admin_Seatcategory.php">Seat Category</a>

    <a href="admin_profile.php">Profile</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>