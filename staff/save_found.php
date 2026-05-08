<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: report_found.php");
    exit();
}

$staff_name = sanitize($_SESSION['staff_name']);
$item_name = sanitize($_POST['item_name']);
$item_color = sanitize($_POST['item_color']);
$brand = sanitize($_POST['brand']);
$item_description = sanitize($_POST['item_description']);
$found_location = sanitize($_POST['found_location']);
$found_date = sanitize($_POST['found_date']);
$storage_location = sanitize($_POST['storage_location']);

$found_code = generateFoundCode();
$photo_path = '';

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['photo']['size'] > MAX_FILE_SIZE) {
        die("Error: File size too large. Max 5MB allowed.");
    }
    $upload = uploadFile($_FILES['photo'], 'found');
    if ($upload) {
        $photo_path = $upload;
    }
}

$query = "INSERT INTO found_items (found_code, staff_name, item_name, item_description, item_color, brand, found_location, found_date, storage_location, photo_path) 
          VALUES ('$found_code', '$staff_name', '$item_name', '$item_description', '$item_color', '$brand', '$found_location', '$found_date', '$storage_location', '$photo_path')";

$success = mysqli_query($conn, $query);
$new_found_id = mysqli_insert_id($conn);

// Auto-match algorithm
$potential_matches = [];
if ($success) {
    $match_query = "SELECT * FROM lost_items 
                    WHERE status = 'pending' 
                    AND lost_date <= '$found_date'
                    AND (item_name LIKE '%$item_name%' OR item_name LIKE '%" . explode(' ', $item_name)[0] . "%' OR item_color = '$item_color')
                    ORDER BY lost_date DESC LIMIT 5";
    $match_result = mysqli_query($conn, $match_query);
    while($row = mysqli_fetch_assoc($match_result)) {
        $potential_matches[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Found Item Saved - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="scene" aria-hidden="true">
  <div class="scene__blob scene__blob--1"></div>
  <div class="scene__blob scene__blob--2"></div>
  <div class="scene__blob scene__blob--3"></div>
</div>

<!-- Theme toggle -->
<button class="glass glass-btn theme-toggle-btn" id="theme-toggle" aria-label="Toggle colour scheme" title="Toggle light / dark mode">
  <span class="icon-dark" aria-hidden="true"><i class="fas fa-sun"></i></span>
  <span class="icon-light" aria-hidden="true"><i class="fas fa-moon"></i></span>
</button>

<main class="page">
  <header class="hero" style="padding-top: 40px; min-height: auto; padding-bottom: 20px;">
    <div style="display: flex; flex-direction: column; align-items: center; gap: 20px; margin-bottom: 30px;">
        <div class="logo-box cascade">
            <img src="../logo.png" alt="Ethiopian Airlines Logo" style="height: 50px;">
        </div>
        <nav class="glass glass-nav">
          <a href="dashboard.php" class="glass-nav__item">Dashboard</a>
          <a href="view_lost.php" class="glass-nav__item">Lost Items</a>
          <a href="view_found.php" class="glass-nav__item">Found Items</a>
          <a href="match_items.php" class="glass-nav__item">Match Items</a>
          <a href="logout.php" class="glass-nav__item" style="color: var(--accent-rose);">Logout</a>
        </nav>
    </div>
  </header>

  <div class="container page" style="max-width: 850px;">
    <?php if ($success): ?>
        <div class="glass glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 4rem; color: var(--accent-lime); margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="glass-card__title" style="font-size: 1.8rem;">Found Item Logged Successfully!</h2>
            <p class="glass-card__body" style="margin-bottom: 30px;">The record has been saved and is now active in the database.</p>
            
            <div style="background: var(--glass-white-md); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--accent-lime); margin-bottom: 40px; display: inline-block;">
                <p class="glass-card__label" style="margin-bottom: 5px;">FOUND CODE</p>
                <h2 style="letter-spacing: 2px; color: var(--accent-lime); margin: 0;"><?= $found_code ?></h2>
            </div>
            
            <?php if (!empty($potential_matches)): ?>
                <div class="glass" style="background: rgba(255, 204, 0, 0.1); border: 1px solid rgba(255, 204, 0, 0.3); border-radius: var(--radius-lg); padding: 30px; text-align: left; margin-bottom: 40px;">
                    <h3 class="glass-card__title" style="color: var(--accent-amber); display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas fa-magic"></i> Potential Matches Found!
                    </h3>
                    <p class="glass-card__body" style="margin-bottom: 20px;">We found <?= count($potential_matches) ?> existing lost reports that might match this item:</p>
                    
                    <div class="glass-table-container" style="background: var(--glass-white);">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Passenger</th>
                                    <th>Item</th>
                                    <th>Date Lost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($potential_matches as $match): ?>
                                <tr>
                                    <td><strong style="color: var(--accent-amber);"><?= $match['claim_code'] ?></strong></td>
                                    <td><?= htmlspecialchars($match['passenger_name']) ?></td>
                                    <td><?= htmlspecialchars($match['item_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($match['lost_date'])) ?></td>
                                    <td>
                                        <form action="process_match.php" method="POST" style="display:inline;" onsubmit="return confirm('Confirm this match?');">
                                            <input type="hidden" name="lost_id" value="<?= $match['id'] ?>">
                                            <input type="hidden" name="found_id" value="<?= $new_found_id ?>">
                                            <button type="submit" class="glass glass-btn glass-btn--primary" style="padding: 8px 15px; font-size: 0.8rem;">
                                                Match
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="view_found.php" class="glass glass-btn glass-btn--ghost">View All Found Items</a>
                <a href="report_found.php" class="glass glass-btn glass-btn--primary">Log Another Item</a>
                <a href="match_items.php" class="glass glass-btn glass-btn--ghost">Manual Match System</a>
            </div>
        </div>
    <?php else: ?>
        <div class="glass glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 4rem; color: var(--accent-rose); margin-bottom: 20px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 class="glass-card__title" style="color: var(--accent-rose);">Submission Failed</h2>
            <p class="glass-card__body">Error saving record: <?= mysqli_error($conn) ?></p>
            <a href="report_found.php" class="glass glass-btn glass-btn--primary" style="margin-top: 30px;">Try Again</a>
        </div>
    <?php endif; ?>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
