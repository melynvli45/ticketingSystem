<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

<section class="profile-section">
      <div class="profile-card">
        <h1>USER PROFILE</h1>
        <div class="profile-img">
          <img src="image/hahahahahahahaha.jpg" alt="" />
        </div>

        <h2>FULL NAME</h2>
        <p class="username">USERNAME</p>

        <div class="info">
          <p>Email: example@gmail.com</p>
          <p>Type : Admin</p>
        </div>

        <a href="admin_profileUpdate.php" class="btn-edit-profile">Edit Profile</a>
      </div>
    </section>

  </body>
</html>
