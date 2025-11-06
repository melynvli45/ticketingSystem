<?php
session_start();
// Simple profile handler: show profile on GET, process updates on POST.
// Important: the update logic below intentionally does NOT modify the User_type column.
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Determine user id (prefer session). Do NOT trust client-supplied user type.
  $userId = null;
  if (!empty($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];
  } elseif (!empty($_POST['user_id']) && ctype_digit((string)$_POST['user_id'])) {
    $userId = (int) $_POST['user_id'];
  }

  if ($userId === null) {
    // No user identifier available; reject the update.
    header('HTTP/1.1 400 Bad Request');
    echo 'Unable to identify user for update.';
    exit;
  }

  $fullName = trim((string)($_POST['full_name'] ?? ''));
  $email = trim((string)($_POST['email'] ?? ''));
  $password = trim((string)($_POST['password'] ?? ''));
  $confirm = trim((string)($_POST['confirm_password'] ?? ''));

  // Basic validation
  if ($fullName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid name or email.';
    exit;
  }

  // If a password was provided, validate and hash it. Otherwise leave it unchanged.
  $params = [':fullname' => $fullName, ':email' => $email, ':id' => $userId];
  if ($password !== '') {
    if (strlen($password) < 8) {
      header('HTTP/1.1 400 Bad Request');
      echo 'Password must be at least 8 characters.';
      exit;
    }
    if ($password !== $confirm) {
      header('HTTP/1.1 400 Bad Request');
      echo 'Passwords do not match.';
      exit;
    }
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'UPDATE users SET Full_name = :fullname, Email = :email, Password = :password WHERE User_ID = :id';
    $params[':password'] = $hashed;
  } else {
    $sql = 'UPDATE users SET Full_name = :fullname, Email = :email WHERE User_ID = :id';
  }

  // Never update User_type from user-submitted data.
  $stmt = $pdo->prepare($sql);
  try {
    $stmt->execute($params);
  } catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Database error: ' . htmlspecialchars($e->getMessage());
    exit;
  }

  // If session stores full name, update it so UI stays consistent.
  if (!empty($_SESSION['full_name']) && $_SESSION['full_name'] !== $fullName) {
    $_SESSION['full_name'] = $fullName;
  }

  // Redirect back to the profile page after successful update.
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
  exit;
}

// Load current user for display
$userId = !empty($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user = ['User_ID' => $userId, 'Full_name' => '', 'Email' => '', 'User_type' => 'user'];
if ($userId > 0) {
  try {
    // Select full row so we adapt to your DB schema (but never expose Password)
    $stmt = $pdo->prepare('SELECT * FROM users WHERE User_ID = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      // merge so missing columns fall back to defaults
      $user = array_merge($user, $row);
    }
  } catch (PDOException $e) {
    // ignore and continue with defaults
  }
}

// determine profile image from common column names, fall back to placeholder
$profile_img = htmlspecialchars($user['Profile_Image'] ?? $user['profile_image'] ?? $user['avatar'] ?? 'image/hahahahahahahaha.jpg');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile</title>
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
        <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
          <a href="home.php">Home</a>
          <a href="admin_Concert.php">Manage Concert</a>
          <div class="dropdown">
            <button class="dropbtn">Booking List</button>
            <div class="dropdown-content">
              <a href="admin_bookpending.php">Pending</a>
              <a href="admin_bookapprove.php">Approved</a>
              <a href="admin_bookcancel.php">Rejected</a>
            </div>
          </div>
          <a href="admin_Seatcategory.php">Seat Category</a>
          <a href="admin_profile.php">Profile</a>
          <a href="logout.php">Log Out</a>
        <?php else: ?>
          <a href="home.php">Home</a>
          <a href="discover.php">Discover</a>
          <a href="ticketpurchase.php">Ticket Purchase</a>
          <a href="viewTicket.php">My Ticket</a>
          <a href="profile.php">Profile</a>
          <a href="logout.php">Log Out</a>
        <?php endif; ?>
      </div>
    </nav>

    <section class="profile-section">
      <div class="profile-card">
        <h1>USER PROFILE</h1>
        <div class="profile-img">
          <img src="<?= htmlspecialchars($profile_img) ?>" alt="Profile image" />
        </div>

        <h2><?= htmlspecialchars($user['Full_name'] ?? 'Full Name') ?></h2>
        <p class="username"><?= htmlspecialchars($user['Email'] ?? '') ?></p>

        <div class="info">
          <p>Email: <?= htmlspecialchars($user['Email'] ?? '') ?></p>
          <p>Type : <?= htmlspecialchars(ucfirst($user['User_type'] ?? 'user')) ?></p>
          <?php if (!empty($user['User_ID'])): ?>
            <p>User ID: <?= htmlspecialchars($user['User_ID']) ?></p>
          <?php endif; ?>
          <?php
            // try common created timestamp column names
            $created = $user['created_at'] ?? $user['created'] ?? $user['created_on'] ?? null;
            if ($created):
          ?>
            <p>Created: <?= htmlspecialchars($created) ?></p>
          <?php endif; ?>
        </div>

        <a href="profileUpdate.php" class="btn-edit-profile">Edit Profile</a>
      </div>
    </section>
  </body>
</html>
