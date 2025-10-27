<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Event</title>
    <link rel="stylesheet" href="admincss.css" />
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

    <div class="eventBox">
      <form action="admin_ConcertAdd.php">
        <h1>UPDATE SEAT CATEGORY</h1>

        <label>CATEGORY ID:</label>
        <input type="text" placeholder="CATEGORY IDENTIFICATION" readonly />

        <label>EVENT ID:</label>
        <input type="text" placeholder="EVENT IDENTIFICATION" readonly />

        <label>PRICE (RM): </label>
        <input type="number" placeholder="0.00" required />

        <label>CATEGORY TYPE: </label>
        <input type="text" placeholder="TYPE" required />

        <div class="btn-container">
          <button class="eventbtn" type="submit">SUBMIT</button>
          <button class="eventbtn" type="reset">CANCEL</button>
        </div>
      </form>
    </div>
  </body>
</html>
