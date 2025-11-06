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
  <a href="logout.php">Log Out</a>
      </div>
    </nav>

    <div class="eventBox">
      <form action="admin_ConcertAdd.php">
        <h1>UPDATE EVENT</h1>

        <label>EVENT ID:</label>
        <input type="text" placeholder="EVENT IDENTIFICATION" readonly />

        <label>NAME: </label>
        <input type="text" placeholder="EVENT NAME" required />

        <label>VENUE: </label>
        <input type="text" placeholder="VENUE" required />

        <label>DATE: </label>
        <input type="date" id="concertDate" required />

        <script>
          // Get today's date
          const today = new Date().toISOString().split("T")[0];
          // Set the minimum date
          document.getElementById("concertDate").setAttribute("min", today);
        </script>

        <label>START TIME: </label>
        <input type="time" required />

        <label>END TIME: </label>
        <input type="time" required />

        <label>POSTER PHOTO (1200 × 1800 px): </label>
        <input type="file" accept="image/png, image/jpeg" />

        <div class="btn-container">
          <button class="eventbtn" type="submit">SUBMIT</button>
          <button class="eventbtn" onclick="window.location.href='admin_Concert.php'">CANCEL</button>
        </div>
      </form>
    </div>
  </body>
</html>
