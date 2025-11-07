<?php
// login.php - basic authentication using users table
session_start();
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'] ?? '';

  if (!$email || $password === '') {
    $error = 'Please enter email and password.';
  } else {
    $stmt = $pdo->prepare('SELECT User_ID, Full_name, Password, User_type FROM users WHERE Email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['Password'])) {
      // Authenticated
      $_SESSION['user_id'] = $user['User_ID'];
      $_SESSION['full_name'] = $user['Full_name'];
      $_SESSION['user_type'] = $user['User_type'];
      // Redirect based on user type:
      // - admins -> pending bookings
      // - regular users -> viewticket page
      // - any other/unknown type -> home
      if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        header('Location: admin_bookpending.php');
        exit;
      } elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
        header('Location: viewticket.php');
        exit;
      }
      header('Location: home.php');
      exit;
    } else {
      $error = 'Invalid email or password.';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="login-page">
    <div class="wrapper">
      <?php if ($error): ?>
        <div class="form-error" style="color:#900;margin-bottom:12px"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post" action="login.php">
        <h1>TIXPOP TICKETING SYSTEM</h1>

        <h5>Please enter your login details</h5>
        <div class="input-box">
          <input type="email" name="email" placeholder="Email" required />
          <img src="image/user.png" />
        </div>

        <div class="input-box">
          <input type="password" name="password" placeholder="Password" required />
          <img src="image/lock.png" />
        </div>

        <button type="submit" class="btn">LOGIN</button>

        <div class="register-link">
          <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
      </form>
    </div>
  </body>
</html>