<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Menu</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="home-page">
    <?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once __DIR__ . '/db.php';
    // require admin
    if (empty($_SESSION['user_type']) || ($_SESSION['user_type'] ?? '') !== 'admin') {
        header('Location: login.php');
        exit;
    }
    ?>
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <div class="title">
      <h1>SEAT CATEGORY</h1>
      <div style="margin-top:12px"><a class="eventbtn" href="admin_SeatAdd.php">+ Add New Category</a></div>
    </div>

    <div class="category-box">
      <?php
      try {
        $cats = $pdo->query('SELECT Category_ID, Category_type, Price, description FROM category ORDER BY Price DESC')->fetchAll();
      } catch (Exception $e) {
        $cats = [];
      }

      if (empty($cats)) {
        echo '<p>No categories available.</p>';
      } else {
        foreach ($cats as $c) {
          echo '<div class="category-card">';
          echo '<h3>' . htmlspecialchars($c['Category_type']) . '</h3>';
          echo '<p>Category ID: ' . (int)$c['Category_ID'] . '<br />PRICE: RM ' . number_format($c['Price'],2) . '<br />' . htmlspecialchars($c['description'] ?? '') . '</p>';
          echo '<div class="rbtn-container">';
          echo '<a href="admin_SeatUpdate.php?id=' . (int)$c['Category_ID'] . '"><button type="button" class="bandbtn">UPDATE</button></a>';
          echo '<form method="post" action="admin_SeatUpdate.php" style="display:inline-block;margin-left:8px">';
          echo '<input type="hidden" name="category_id" value="' . (int)$c['Category_ID'] . '" />';
          echo '<button class="bandbtn" type="submit" name="delete_category" value="1" onclick="return confirm(\'Are you sure you want to delete this category?\')">DELETE</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
    </div>
  </body>
</html>
