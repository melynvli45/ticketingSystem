<?php
session_start();
require_once __DIR__ . '/db.php';

// Only allow admins
if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

$show_force_confirm = false;

// determine if we're editing an existing event
$editing = false;
$editingEvent = null;
if (!empty($_GET['id']) && ctype_digit((string)$_GET['id'])) {
  $editing = true;
  $eid = (int)$_GET['id'];
  $editingEvent = $pdo->prepare('SELECT * FROM event WHERE Event_ID = ?');
  $editingEvent->execute([$eid]);
  $editingEvent = $editingEvent->fetch();
}

// fetch categories for select
$categories = $pdo->query('SELECT Category_ID, Category_type FROM category ORDER BY Category_type ASC')->fetchAll();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // handle delete action first
  if (!empty($_POST['delete_event']) && !empty($_POST['event_id']) && ctype_digit((string)$_POST['event_id'])) {
    $toDelete = (int)$_POST['event_id'];
    try {
      // check for dependent invoices
      $cntStmt = $pdo->prepare('SELECT COUNT(*) FROM invoice WHERE Event_ID = ?');
      $cntStmt->execute([$toDelete]);
      $invoiceCount = (int)$cntStmt->fetchColumn();

      // if there are invoices and the admin didn't confirm force delete, show confirmation option
      if ($invoiceCount > 0 && empty($_POST['force_delete'])) {
        $errors[] = "There are {$invoiceCount} invoice(s) associated with this event. To delete the event you must first remove those invoices or choose 'Force delete' which will also delete related invoices and payments.";
        // set flag to show force-delete button in the form
        $show_force_confirm = true;
      } else {
        // perform delete (if force_delete specified, remove dependent rows first)
        // fetch poster path to delete file
        $q = $pdo->prepare('SELECT poster FROM event WHERE Event_ID = ?');
        $q->execute([$toDelete]);
        $row = $q->fetch();

        // delete inside transaction when dependent rows exist
        $pdo->beginTransaction();
        if ($invoiceCount > 0) {
          // fetch invoice ids
          $invStmt = $pdo->prepare('SELECT Invoice_ID FROM invoice WHERE Event_ID = ?');
          $invStmt->execute([$toDelete]);
          $invIds = $invStmt->fetchAll(PDO::FETCH_COLUMN);
          if (!empty($invIds)) {
            // delete payments linked to those invoices (if payment table has Invoice_ID FK)
            $inClause = implode(',', array_fill(0, count($invIds), '?'));
            $delPayments = $pdo->prepare("DELETE FROM payment WHERE Invoice_ID IN ($inClause)");
            $delPayments->execute($invIds);

            // delete invoices
            $delInvoices = $pdo->prepare("DELETE FROM invoice WHERE Invoice_ID IN ($inClause)");
            $delInvoices->execute($invIds);
          }
        }

        // delete event
        $del = $pdo->prepare('DELETE FROM event WHERE Event_ID = ?');
        $del->execute([$toDelete]);

        // remove poster file after successful DB deletes
        if ($row && !empty($row['poster'])) {
          $posterPath = __DIR__ . '/' . $row['poster'];
          if (file_exists($posterPath)) {
            @unlink($posterPath);
          }
        }

        $pdo->commit();
        header('Location: discover.php');
        exit;
      }
    } catch (PDOException $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $errors[] = 'Unable to delete event: ' . $e->getMessage();
    }
  }

  $name = trim($_POST['name'] ?? '');
    $venue = trim($_POST['venue'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $category_id = !empty($_POST['category_id']) && ctype_digit((string)$_POST['category_id']) ? (int)$_POST['category_id'] : null;

  if ($name === '' || $venue === '' || $date === '') {
    $errors[] = 'Please fill in name, venue and date.';
  } else {
    // handle poster upload if provided
    $posterFilename = null;
    if (!empty($_FILES['poster']) && isset($_FILES['poster']['error']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
      $tmp = $_FILES['poster']['tmp_name'];
      $orig = $_FILES['poster']['name'];
      $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg','jpeg','png'])) {
        $errors[] = 'Poster must be a JPG or PNG image.';
      } else {
        $safeBase = preg_replace('/[^a-z0-9-_\.]/i', '-', pathinfo($orig, PATHINFO_FILENAME));
        $posterFilename = 'image/' . time() . '_' . $safeBase . '.' . $ext;
        if (!move_uploaded_file($tmp, __DIR__ . '/' . $posterFilename)) {
          $errors[] = 'Unable to save uploaded poster file.';
          $posterFilename = null;
        }
      }
    }

    try {
      if (!empty($_POST['event_id']) && ctype_digit((string)$_POST['event_id'])) {
        // update existing
        $eventId = (int)$_POST['event_id'];
        // if editingEvent exists and no new poster uploaded, keep existing poster
        $existingPoster = $editingEvent['poster'] ?? null;
        if ($posterFilename === null && $existingPoster) {
          $posterFilename = $existingPoster;
        }

        if ($posterFilename !== null) {
          $stmt = $pdo->prepare('UPDATE event SET Date = ?, Time = ?, Venue = ?, Name = ?, Category_ID = ?, poster = ? WHERE Event_ID = ?');
          $stmt->execute([$date, $time ?: null, $venue, $name, $category_id, $posterFilename, $eventId]);
        } else {
          $stmt = $pdo->prepare('UPDATE event SET Date = ?, Time = ?, Venue = ?, Name = ?, Category_ID = ? WHERE Event_ID = ?');
          $stmt->execute([$date, $time ?: null, $venue, $name, $category_id, $eventId]);
        }
        $success = 'Event updated successfully.';
      } else {
        // insert new
        $stmt = $pdo->prepare('INSERT INTO event (Date, Time, Venue, Name, Category_ID, poster) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$date, $time ?: null, $venue, $name, $category_id, $posterFilename]);
        $success = 'Event added successfully.';
      }
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
    <title>Add Concert</title>
    <link rel="stylesheet" href="admincss.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>

  <body class="other-page">
    <?php include __DIR__ . '/admin_anavbar.php'; ?>

    <div class="eventBox">
      <h1>PLEASE ENTER THE FOLLOWING DETAILS</h1>

      <?php if ($success): ?>
        <div style="color:green;margin-bottom:12px"><?=htmlspecialchars($success)?></div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div style="color:#900;margin-bottom:12px"><?=htmlspecialchars(implode('\n',$errors))?></div>
      <?php endif; ?>

      <form action="admin_ConcertAdd.php<?= $editing ? '?id=' . (int)$editingEvent['Event_ID'] : '' ?>" method="post" enctype="multipart/form-data">

        <?php if ($editing && !empty($editingEvent)): ?>
          <input type="hidden" name="event_id" value="<?= (int)$editingEvent['Event_ID'] ?>" />
        <?php endif; ?>

        <label>NAME: </label>
  <input type="text" name="name" value="<?=htmlspecialchars($_POST['name'] ?? $editingEvent['Name'] ?? '')?>" required />

        <label>VENUE: </label>
  <input type="text" name="venue" value="<?=htmlspecialchars($_POST['venue'] ?? $editingEvent['Venue'] ?? '')?>" required />

        <label>DATE: </label>
  <input type="date" id="concertDate" name="date" value="<?=htmlspecialchars($_POST['date'] ?? $editingEvent['Date'] ?? '')?>" required />

        <script>
          // Get today's date
          const today = new Date().toISOString().split("T")[0];
          // Set the minimum date
          document.getElementById("concertDate").setAttribute("min", today);
        </script>

        <label>START TIME: </label>
  <input type="time" name="time" value="<?=htmlspecialchars($_POST['time'] ?? $editingEvent['Time'] ?? '')?>" />

        <label>Category: </label>
        <select name="category_id">
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $c): ?>
            <?php $selected = false; if (!empty($_POST['category_id']) && $_POST['category_id']==$c['Category_ID']) $selected = true; elseif (!empty($editingEvent) && $editingEvent['Category_ID']==$c['Category_ID']) $selected = true; ?>
            <option value="<?= (int)$c['Category_ID'] ?>" <?= $selected ? 'selected' : '' ?>><?=htmlspecialchars($c['Category_type'])?></option>
          <?php endforeach; ?>
        </select>

        <label>POSTER PHOTO (optional): </label>
        <input type="file" name="poster" accept="image/png, image/jpeg" />
        <?php if ($editing && !empty($editingEvent['poster'])): ?>
          <div style="margin-top:8px">Current poster: <br /><img src="<?= htmlspecialchars($editingEvent['poster']) ?>" alt="poster" style="max-width:200px;display:block;margin-top:6px" /></div>
        <?php endif; ?>

        <div class="btn-container">
          <button class="eventbtn" type="submit">SUBMIT</button>
          <button class="eventbtn" type="reset">CANCEL</button>
          <?php if ($editing && !empty($editingEvent)): ?>
            <button class="eventbtn" type="submit" name="delete_event" value="1" onclick="return confirm('Are you sure you want to delete this event? This cannot be undone.')" style="background:#c33;margin-left:8px">DELETE</button>
            <?php if (!empty($show_force_confirm)): ?>
              <button class="eventbtn" type="submit" name="force_delete" value="1" onclick="return confirm('Force delete will also remove related invoices and payments. Continue?')" style="background:#800;margin-left:8px">FORCE DELETE (remove invoices)</button>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </body>
</html>
