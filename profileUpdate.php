<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css" />
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
        <a href="discover.php">Discover</a>
        <a href="seatCategory.php">Seat Category</a>
        <a href="ticketpurchase.php">Ticket Purchase</a>
        <a href="viewTicket.php">My Ticket</a>
        <a href="profile.php">Profile</a>
        <a href="index.php">Log Out</a>
      </div>
    </nav>

    <div class="updateprofile">
      <form action="profile.php">
        <h1>UPDATE YOUR PROFILE</h1>

        <label>USERNAME:</label>
        <input type="text" placeholder="USERNAME" readonly />

        <label>FULL NAME: </label>
        <input type="text" placeholder="FULL NAME" />

        <label>EMAIL: </label>
        <input type="text" placeholder="example@gmail.com" />

        <label>TYPE:</label>
        <input type="text" placeholder="ADMIN" readonly/>

        <div class="btn-container">
          <button class="pro-btn" type="submit">SAVE</button>

          <a href="profile.php">
            <button class="pro-btn" type="submit">CANCEL</button>
          </a>
        </div>
      </form>
    </div>
  </body>
</html>
