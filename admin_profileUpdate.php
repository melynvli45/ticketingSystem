<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Profile</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="other-page">
    <?php
    session_start();
    require_once __DIR__ . '/db.php';
    $user = ['User_ID' => '', 'Full_name' => '', 'Email' => '', 'User_type' => 'admin'];
    if (!empty($_SESSION['User_ID'])) {
        $stmt = $pdo->prepare('SELECT User_ID, Full_name, Email, User_type FROM users WHERE USER_ID = ?');
        $stmt->execute([(int)$_SESSION['User_ID']]);
        $row = $stmt->fetch();
        if ($row) {
            $user = $row;
        }
    }
    ?>
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <div class="updateprofile">
      <form action="profile.php" method="post">
        <h1>UPDATE YOUR PROFILE</h1>
        <label>USERNAME:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" readonly />

        <label>FULL NAME: </label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['Full_name'] ?? '') ?>" required />

        <label>EMAIL: </label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" required />

        <label>TYPE:</label>
        <!-- displayed but not named so it cannot be changed via the form -->
        <input type="text" value="<?= strtoupper(htmlspecialchars($user['User_type'] ?? 'admin')) ?>" readonly/>

        <?php if (!empty($user['User_ID'])): ?>
          <input type="hidden" name="User_ID" value="<?= (int)$user['User_ID'] ?>" />
        <?php endif; ?>

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
