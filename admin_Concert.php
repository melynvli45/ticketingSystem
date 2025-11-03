<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Concert List</title>
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
      <h1>CONCERTS FOR YOU</h1>
      <div style="margin-top:12px"><a class="eventbtn" href="admin_ConcertAdd.php">+ Add New Event</a></div>
    </div>

    <div class="band-box">
    <?php
      // load events from DB
      try {
        $stmt = $pdo->query('SELECT Event_ID, Name, Date, Time, Venue, poster FROM event ORDER BY Date ASC');
        $events = $stmt->fetchAll();
      } catch (Exception $e) {
        $events = [];
      }

      function posterForNameAdmin($name) {
        $n = strtolower($name);
        if (stripos($n, 'black') !== false || stripos($n, 'blackpink') !== false) return 'image/blackpink.png';
        if (stripos($n, 'stray') !== false || stripos($n, 'stray kids') !== false) return 'image/straykids.jpg';
        if (stripos($n, 'new') !== false || stripos($n, 'newjeans') !== false) return 'image/newjeans.png';
        return 'image/tick1.jpg';
      }

      if (empty($events)) {
        echo '<p>No events found.</p>';
      } else {
        foreach ($events as $ev) {
          $poster = null;
          if (!empty($ev['poster']) && file_exists(__DIR__ . '/' . $ev['poster'])) {
            $poster = $ev['poster'];
          } else {
            $poster = posterForNameAdmin($ev['Name']);
          }
          $displayDate = htmlspecialchars($ev['Date']);
          $displayTime = htmlspecialchars($ev['Time'] ?? 'TBA');
          $venue = htmlspecialchars($ev['Venue'] ?? 'TBA');
          $name = htmlspecialchars($ev['Name'] ?? 'Unnamed Event');
          $eid = (int)$ev['Event_ID'];
          echo '<div class="band-card">';
          echo '<div class="img-wrapper"><img src="' . htmlspecialchars($poster) . '" alt="' . $name . '" /></div>';
          echo '<h3>' . $name . '</h3>';
          echo '<p>EVENT ID: ' . $eid . '<br>DATE: ' . $displayDate . '<br>START TIME: ' . $displayTime . '<br>VENUE: ' . $venue . '</p>';
          echo '<div class="rbtn-container">';
          echo '<a href="admin_ConcertAdd.php?id=' . $eid . '"><button type="button" class="bandbtn">UPDATE</button></a>';
          // delete form posts to admin_ConcertAdd.php to use existing delete handler
          echo '<form method="post" action="admin_ConcertAdd.php" style="display:inline-block;margin-left:8px">';
          echo '<input type="hidden" name="event_id" value="' . $eid . '" />';
          echo '<button class="bandbtn" type="submit" name="delete_event" value="1" onclick="return confirm(\'Are you sure you want to delete this concert? This will remove the event (and may remove related invoices if you choose force).\')">DELETE</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        }
      }
    ?>
    </div>

  </body>
</html>
