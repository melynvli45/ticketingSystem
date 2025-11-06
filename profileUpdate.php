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
    <?php
    session_start();
    require_once __DIR__ . '/db.php';
  $user = ['User_ID' => '', 'Full_name' => '', 'Email' => '', 'User_type' => 'user'];
  // Errors for inline display
  $errors = [];

  // Process POST on this page so we can show inline validation errors
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = null;
    if (!empty($_SESSION['user_id'])) {
      $userId = (int)$_SESSION['user_id'];
    } elseif (!empty($_POST['user_id']) && ctype_digit((string)$_POST['user_id'])) {
      $userId = (int)$_POST['user_id'];
    }

    if ($userId === null) {
      $errors['general'] = 'Unable to identify user for update.';
    } else {
      $fullName = trim((string)($_POST['full_name'] ?? ''));
      $email = trim((string)($_POST['email'] ?? ''));
      $password = trim((string)($_POST['password'] ?? ''));
      $confirm = trim((string)($_POST['confirm_password'] ?? ''));

      if ($fullName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['general'] = 'Invalid name or email.';
      }

      if ($password !== '') {
        if (strlen($password) < 8) {
          $errors['password'] = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
          $errors['password'] = 'Passwords do not match.';
        }
      }

      if (empty($errors)) {
        // perform update (never update User_type)
        try {
          if ($password !== '') {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'UPDATE users SET Full_name = :fullname, Email = :email, Password = :password WHERE User_ID = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':fullname' => $fullName, ':email' => $email, ':password' => $hashed, ':id' => $userId]);
          } else {
            $sql = 'UPDATE users SET Full_name = :fullname, Email = :email WHERE User_ID = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':fullname' => $fullName, ':email' => $email, ':id' => $userId]);
          }

          // update session full name if present
          if (!empty($_SESSION['full_name'])) {
            $_SESSION['full_name'] = $fullName;
          }

          // Success -> redirect back to profile page
          header('Location: profile.php');
          exit;
        } catch (PDOException $e) {
          $errors['general'] = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
      }
    }
  }

  // Load user for display (use session id)
  if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT User_ID, Full_name, Email, User_type FROM users WHERE User_ID = ?');
    $stmt->execute([(int)$_SESSION['user_id']]);
    $row = $stmt->fetch();
    if ($row) {
      $user = $row;
    }
  }
    ?>
    <nav class="navbar">
      <div class="logo">TixPop</div>

      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="discover.php">Discover</a>
        
        <a href="ticketpurchase.php">Ticket Purchase</a>
        <a href="viewTicket.php">My Ticket</a>
  <a href="profile.php">Profile</a>
  <a href="logout.php">Log Out</a>
      </div>
    </nav>

    <div class="updateprofile">
      <form action="profileUpdate.php" method="post">
        <h1>UPDATE YOUR PROFILE</h1>

        <label>USERNAME:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" readonly />

  <label>FULL NAME: </label>
  <input type="text" name="full_name" value="<?= htmlspecialchars($user['Full_name'] ?? '') ?>" required />

  <label>EMAIL: </label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" required />

        <label>NEW PASSWORD: </label>
        <input type="password" name="password" placeholder="Leave blank to keep current password" />
        <?php if (!empty($errors['password'])): ?>
          <div class="form-error" style="color:#900;margin-top:6px"><?=htmlspecialchars($errors['password'])?></div>
        <?php endif; ?>

        <label>CONFIRM NEW PASSWORD: </label>
        <input type="password" name="confirm_password" placeholder="Repeat new password" />

        <label>TYPE:</label>
        <!-- show user type but do NOT give it a name (so it can't be directly submitted/changed) -->
        <input type="text" value="<?= strtoupper(htmlspecialchars($user['User_type'] ?? 'user')) ?>" readonly/>

        <?php if (!empty($user['User_ID'])): ?>
          <!-- fallback identifier if session isn't available for some reason -->
          <input type="hidden" name="user_id" value="<?= (int)$user['User_ID'] ?>" />
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
