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

        <div class="rbtn-container">
        <a href="admin_ConcertUpdate.php">
        <button type="submit" class="bandbtn">UPDATE</button>
        </a>
        <button class="bandbtn" onclick="deleteItem()">DELETE</button>
        </div>
      </div>

      <div class="category-card">
        <h3>[EVENT ID 2]</h3>
        <p>
          Category ID: IDID2<br />
          PRICE: RM 5009.00<br />
          CATEGORY TYPE: GALAXY
        </p>

        <div class="rbtn-container">
        <a href="admin_SeatUpdate.php">
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
