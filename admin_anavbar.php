<!--Admin navbar-->

<nav class="navbar">
  <div class="logo">TixPop</div>

  <div class="nav-links">
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
        <a href="admin_bookapprove.php"> Approved</a>
        <a href="admin_bookcancel.php">Rejected</a>
      </div>
    </div>

    <div class="dropdown">
      <button class="dropbtn">Seat Category</button>
      <div class="dropdown-content">
        <a href="admin_SeatAdd.php">Add Category</a>
        <a href="admin_Seatcategory.php"> Seat List</a>
      </div>
    </div>

    <a href="admin_profile.php">Profile</a>
    <a href="index.php">Log Out</a>
  </div>
</nav>
