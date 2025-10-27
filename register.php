<?php
// register.php - handle user registration
session_start();
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
  $username = trim($_POST['username'] ?? '');
  $full_name = trim($_POST['full_name'] ?? $username);
  $password = $_POST['password'] ?? '';
  $password_confirm = $_POST['password_confirm'] ?? '';

  if (!$email) {
    $error = 'Please enter a valid email.';
  } elseif ($password === '') {
    $error = 'Please enter a password.';
  } elseif ($password !== $password_confirm) {
    $error = 'Passwords do not match.';
  } else {
    // Check if email exists
    $stmt = $pdo->prepare('SELECT User_ID FROM users WHERE Email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
      $error = 'Email is already registered.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $insert = $pdo->prepare('INSERT INTO users (Full_name, Email, Password, User_type) VALUES (?, ?, ?, ?)');
      $insert->execute([$full_name, $email, $hash, 'user']);
      header('Location: login.php');
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
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
      <form method="post" action="register.php"> <!--Nanti masa tukar jadi php tu--> 
        <h1>TIXPOP TICKETING SYSTEM</h1>

        <h5>Please enter your details</h5>
        <div class="input-box">
          <input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" placeholder="Email" required />
        </div>

        <div class="input-box">
          <input type="text" name="username" value="<?=htmlspecialchars($_POST['username'] ?? '')?>" placeholder="Username" required />
        </div>

        <div class="input-box">
          <input type="text" name="full_name" value="<?=htmlspecialchars($_POST['full_name'] ?? '')?>" placeholder="Full Name" required />
        </div>

        <div class="input-box">
          <input type="password" name="password" placeholder="Password" required />
        </div>

        <div class="input-box">
          <input type="password" name="password_confirm" placeholder="Confirm Password" required />
        </div>

        <div class="rbtn-container">
        <button type="submit" class="registerbtn">REGISTER</button>
        <button type="reset" class="registerbtn">RESET</button>
        </div>

        <div class="register-link">
          <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
        
      </form>
    </div>
  </body>
</html>
