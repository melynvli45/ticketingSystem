<?php
session_start();

// --- Database Connection ---
// ASSUMPTION: This file contains your PDO connection ($pdo variable).
require_once __DIR__ . '/db.php'; 

// --- Access Control ---
// Ensure the user is logged in and has the 'admin' type.
if (empty($_SESSION['user_id']) || empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to a secure page or login if not authorized
    header('Location: index.php'); 
    exit;
}

// --- Data Retrieval ---
$userId = (int) $_SESSION['user_id'];

// Default data in case the database query fails or the admin record is missing

$user = ['User_ID' => $userId, 'Full_name' => 'Admin Name', 'Email' => 'admin@example.com', 'User_type' => 'admin'];

try {
  // Select all columns for flexibility; we'll only display non-sensitive fields.
  $sql = 'SELECT * FROM users WHERE User_ID = ?';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$userId]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // merge returned columns into the $user array so missing columns fall back to defaults
    $user = array_merge($user, $row);
  }
} catch (PDOException $e) {
  // Log or ignore; show defaults below
}

$profile_img = htmlspecialchars($user['Profile_Image'] ?? $user['profile_image'] ?? $user['avatar'] ?? 'image/hahahahahahahaha.jpg');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Profile</title>
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
        <h1>ADMIN PROFILE</h1>
        <div class="profile-img">
          <img 
            src="<?= $profile_img ?>" 
            alt="<?= htmlspecialchars($user['Full_name'] ?? 'Admin') ?>'s Profile Image" 
          />
        </div>

        <h2><?= htmlspecialchars($user['Full_name'] ?? 'FULL NAME') ?></h2>
        <p class="username"><?= htmlspecialchars($user['Email'] ?? 'USERNAME') ?></p>

        <div class="info">
          <p>User ID: <?= htmlspecialchars($user['User_ID'] ?? $userId) ?></p>
          <p>Email: <?= htmlspecialchars($user['Email'] ?? 'example@gmail.com') ?></p>
          <p>Type : <?= htmlspecialchars(ucfirst($user['User_type'] ?? 'Admin')) ?></p>
          <?php if (!empty($user['created_at']) || !empty($user['created']) || !empty($user['created_on'])): ?>
            <p>Created: <?= htmlspecialchars($user['created_at'] ?? $user['created'] ?? $user['created_on']) ?></p>
          <?php endif; ?>
        </div>

  <a href="profileUpdate.php" class="btn-edit-profile">Edit Profile</a>
      </div>
    </section>

  </body>
</html>