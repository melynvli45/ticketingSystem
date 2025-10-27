<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Menu</title>
    <link rel="stylesheet" href="admincss.css" />
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
            <a href="admin_bookapprove.php">  Approved</a>
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

    <div class="title">
      <h1>WORLD BAND<br />BIGGEST CONCERT</h1>
    </div>

    <div class="description">
      <p>
        Welcome to the World’s Biggest Band Concert Admin Panel. Here,
        administrators can efficiently manage every aspect of this global music
        event — from artist scheduling to ticket sales and attendee data. This
        platform enables seamless coordination between performers, staff, and
        management teams to ensure smooth event operations.<br />
        As an admin, you can update event information, monitor ticket
        availability, oversee registration details, and manage real-time
        performance schedules. Maintain the highest standards of organization
        and ensure that every detail contributes to delivering a world-class
        concert experience for music fans across the globe.
      </p>
    </div>

    <a href="admin_Concert.php">
      <button type="button" class="btn">Manage Concert</button>
    </a>
  </body>
</html>
