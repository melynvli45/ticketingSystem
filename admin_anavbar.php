<nav class="navbar">
  <div class="logo">TixPop</div>

  <div class="nav-links">
    <a href="home.php">Home</a>
    <!-- Manage Concert: single link to concert list (click shows admin_Concert.php) -->
    <a href="admin_Concert.php">Manage Concert</a>
    <?php
    // By default render Booking List as a dropdown. Pages can set $admin_simple_booking = true
    // before including this file to force a simple link instead.
    $admin_simple_booking = isset($admin_simple_booking) && $admin_simple_booking === true;
    if ($admin_simple_booking):
    ?>
      <a href="admin_bookpending.php">Booking List</a>
    <?php else: ?>
      <div class="dropdown">
        <a href="#" class="dropbtn">Booking List</a>
        <div class="dropdown-content">
          <a href="admin_bookpending.php">Pending</a>
          <a href="admin_bookapprove.php">Approved</a>
          <a href="admin_bookcancel.php">Rejected</a>
        </div>
      </div>
    <?php endif; ?>

    <!-- Seat Category: direct link to seat list -->
    <a href="admin_Seatcategory.php">Seat Category</a>

    <a href="admin_profile.php">Profile</a>
    <a href="logout.php">Log Out</a>
  </div>
</nav>