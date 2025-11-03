<?php
// admin_SeatUpdate.php - edit or delete a seat category
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// Only admins
if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$errors = [];
$success = '';

$catId = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['category_id'] ?? 0);
if ($catId <= 0) {
  header('Location: admin_Seatcategory.php');
  exit;
}

// Handle POST (update or delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete_category'])) {
    // Attempt to delete; check for dependent invoices
    try {
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM invoice WHERE Category_ID = ?');
      $stmt->execute([$catId]);
      $count = (int)$stmt->fetchColumn();
      if ($count > 0) {
        $errors[] = "Cannot delete category: $count invoice(s) reference this category.";
      } else {
        $del = $pdo->prepare('DELETE FROM category WHERE Category_ID = ?');
        $del->execute([$catId]);
        header('Location: admin_Seatcategory.php?msg=deleted');
        exit;
      }
    } catch (Exception $e) {
      $errors[] = 'Database error: ' . $e->getMessage();
    }
  } else {
    // Update
    $category_type = trim($_POST['category_type'] ?? '');
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
    $description = trim($_POST['description'] ?? '');

    if ($category_type === '' || $price <= 0) {
      $errors[] = 'Please provide a valid category type and a positive price.';
    } else {
      try {
        $up = $pdo->prepare('UPDATE category SET Price = ?, Category_type = ?, description = ? WHERE Category_ID = ?');
        $up->execute([$price, $category_type, $description, $catId]);
        $success = 'Category updated successfully.';
      } catch (Exception $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
      }
    }
  }
}

// Load current category
try {
  $stmt = $pdo->prepare('SELECT Category_ID, Category_type, Price, description FROM category WHERE Category_ID = ?');
  $stmt->execute([$catId]);
  $cat = $stmt->fetch();
  if (!$cat) {
    header('Location: admin_Seatcategory.php');
    exit;
  }
} catch (Exception $e) {
  $errors[] = 'Database error: ' . $e->getMessage();
  $cat = ['Category_ID' => $catId, 'Category_type' => '', 'Price' => 0.0, 'description' => ''];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Update Seat Category</title>
    <link rel="stylesheet" href="admincss.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  </head>
  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <div class="eventBox">
      <h1>UPDATE SEAT CATEGORY</h1>

      <?php if ($success): ?>
        <div style="color:green;margin-bottom:12px"><?=htmlspecialchars($success)?></div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div style="color:#900;margin-bottom:12px"><?=htmlspecialchars(implode("\n", $errors))?></div>
      <?php endif; ?>

      <form method="post" action="admin_SeatUpdate.php">
        <input type="hidden" name="category_id" value="<?= (int)$cat['Category_ID'] ?>" />

        <label>CATEGORY ID:</label>
        <input type="text" readonly value="<?= (int)$cat['Category_ID'] ?>" />

        <label>CATEGORY TYPE:</label>
        <input type="text" name="category_type" required value="<?= htmlspecialchars($cat['Category_type']) ?>" />

        <label>PRICE (RM):</label>
        <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars(number_format($cat['Price'],2,'.','')) ?>" />

        <label>DESCRIPTION (optional):</label>
        <textarea name="description"><?=htmlspecialchars($cat['description'] ?? '')?></textarea>

        <div class="btn-container">
          <button class="eventbtn" type="submit">SAVE</button>
          <a href="admin_Seatcategory.php"><button class="eventbtn" type="button">CANCEL</button></a>
        </div>
      </form>

      <form method="post" action="admin_SeatUpdate.php" onsubmit="return confirm('Delete this category? This cannot be undone if there are no dependent invoices.')" style="margin-top:12px">
        <input type="hidden" name="category_id" value="<?= (int)$cat['Category_ID'] ?>" />
        <button class="bandbtn" type="submit" name="delete_category" value="1">DELETE CATEGORY</button>
      </form>
    </div>
  </body>
</html>
