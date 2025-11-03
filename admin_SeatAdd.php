<?php
session_start();
require_once __DIR__ . '/db.php';

// Only admins
if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_type = trim($_POST['category_type'] ?? '');
  $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
  $description = trim($_POST['description'] ?? '');

  if ($category_type === '' || $price <= 0) {
    $errors[] = 'Please provide a category type and a positive price.';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO category (Price, Category_type, description) VALUES (?, ?, ?)');
      $stmt->execute([$price, $category_type, $description]);
      $success = 'Category added successfully.';
    } catch (PDOException $e) {
      $errors[] = 'Database error: ' . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Category</title>
    <link rel="stylesheet" href="admincss.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  </head>

  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <div class="eventBox">
      <h1>ADD SEAT CATEGORY</h1>

      <?php if ($success): ?>
        <div style="color:green;margin-bottom:12px"><?=htmlspecialchars($success)?></div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div style="color:#900;margin-bottom:12px"><?=htmlspecialchars(implode('\n',$errors))?></div>
      <?php endif; ?>

      <form method="post" action="admin_SeatAdd.php">
        <label>CATEGORY TYPE:</label>
        <input type="text" name="category_type" required value="<?=htmlspecialchars($_POST['category_type'] ?? '')?>" />

        <label>PRICE (RM):</label>
        <input type="number" step="0.01" name="price" required value="<?=htmlspecialchars($_POST['price'] ?? '')?>" />

        <label>DESCRIPTION (optional):</label>
        <textarea name="description"><?=htmlspecialchars($_POST['description'] ?? '')?></textarea>

        <div class="btn-container">
          <button class="eventbtn" type="submit">SUBMIT</button>
          <button class="eventbtn" type="reset">CANCEL</button>
        </div>
      </form>
    </div>
  </body>
</html>
