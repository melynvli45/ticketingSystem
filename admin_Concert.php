<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Concert List</title>
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

      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search concerts" />
        <button class="search-btn"><i class='bx bx-search'></i></button>
      </div>
    </nav>

    <div class="title">
      <h1>CONCERTS FOR YOU</h1>
    </div>

    <!--Ni just untuk bayangan je, kalau nak ubah, dipersilakan :)-->>
    <div class="band-box">
      <div class="band-card">
        <div class="img-wrapper">
          <img src="image/katseye1.jpg" alt="" />
        </div>
        <h3>KATSEYE :<br />THE BEAUTIFUL CHAOS TOUR</h3>
        <p>EVENT ID: [IDIDIDID]<br>DATE: 21TH SEPTEMBER 2025<br />START TIME: 5:00 PM<br>END TIME: 9:00PM</TIME></p>

        <div class="rbtn-container">
        <a href="admin_ConcertUpdate.php">
        <button type="submit" class="bandbtn">UPDATE</button>
        </a>
        <button class="bandbtn" onclick="deleteItem()">DELETE</button>
        </div>
      </div>
    </div>

    <script>
      function deleteItem(button) {
        if (confirm("Are you sure you want to delete this concert?")) {
          // Find the closest band card and remove it
          const card = button.closest(".band-card");
          card.remove();
        }
      }
    </script>

  </body>
</html>
