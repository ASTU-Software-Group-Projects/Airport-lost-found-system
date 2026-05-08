<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: report.php");
    exit();
}

$passenger_name = sanitize($_POST['passenger_name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);
$item_name = sanitize($_POST['item_name']);
$item_color = sanitize($_POST['item_color']);
$brand = sanitize($_POST['brand']);
$item_description = sanitize($_POST['item_description']);
$lost_location = sanitize($_POST['lost_location']);
$lost_date = sanitize($_POST['lost_date']);

$claim_code = generateClaimCode();
$photo_path = '';

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['photo']['size'] > MAX_FILE_SIZE) {
        die("Error: File size too large. Max 5MB allowed.");
    }
    $upload = uploadFile($_FILES['photo'], 'lost');
    if ($upload) {
        $photo_path = $upload;
    }
}

$query = "INSERT INTO lost_items (claim_code, passenger_name, email, phone, item_name, item_description, item_color, brand, lost_location, lost_date, photo_path) 
          VALUES ('$claim_code', '$passenger_name', '$email', '$phone', '$item_name', '$item_description', '$item_color', '$brand', '$lost_location', '$lost_date', '$photo_path')";

if (mysqli_query($conn, $query)) {
    $success = true;
} else {
    $success = false;
    $error = mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Saved - <?= SITE_NAME ?></title>
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
          <a href="../index.php" class="glass-nav__item">Home</a>
          <a href="report.php" class="glass-nav__item">Report Lost</a>
          <a href="check_status.php" class="glass-nav__item">Check Status</a>
          <a href="../staff/login.php" class="glass-nav__item">Staff Portal</a>
        </nav>
    </div>
  </header>

  <div class="container page" style="max-width: 650px;">
    <?php if ($success): ?>
        <div class="glass glass-card" style="text-align: center; padding: 50px 30px;">
            <div style="font-size: 4.5rem; color: var(--accent-lime); margin-bottom: 25px; filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.4));">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="glass-card__title" style="font-size: 2rem; margin-bottom: 15px;">Report Submitted!</h2>
            <p class="glass-card__body" style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 40px;">
                Your report has been logged. Please save the claim code below to track your item's status.
            </p>
            
            <div style="background: var(--glass-white-md); padding: 30px; border-radius: var(--radius-lg); border: 2px dashed var(--accent-amber); margin-bottom: 40px; position: relative;">
                <p class="glass-card__label" style="text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Your Claim Code</p>
                <h1 id="claimCodeDisplay" style="font-size: 3rem; letter-spacing: 5px; color: var(--accent-amber); font-weight: 800; margin-bottom: 15px;"><?= $claim_code ?></h1>
                <button onclick="copyToClipboard('claimCodeDisplay')" class="glass glass-btn" style="padding: 10px 20px; font-size: 0.9rem; margin: 0 auto;">
                    <i class="fas fa-copy"></i> Copy Code
                </button>
            </div>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="view_status.php?code=<?= $claim_code ?>" class="glass glass-btn glass-btn--primary" style="padding: 15px 30px;">
                    Check Status Now
                </a>
                <a href="report.php" class="glass glass-btn glass-btn--ghost" style="padding: 15px 30px;">
                    Report Another
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="glass glass-card" style="text-align: center; padding: 50px 30px; border-color: rgba(238, 28, 35, 0.3);">
            <div style="font-size: 4.5rem; color: var(--accent-rose); margin-bottom: 25px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 class="glass-card__title" style="color: var(--accent-rose);">Submission Failed</h2>
            <p class="glass-card__body" style="margin-bottom: 30px;">
                Sorry, there was an error processing your report. Please try again.
            </p>
            <p style="font-family: monospace; font-size: 0.8rem; opacity: 0.6; margin-bottom: 30px;">
                Error: <?= htmlspecialchars($error) ?>
            </p>
            <a href="report.php" class="glass glass-btn glass-btn--primary">Go Back & Try Again</a>
        </div>
    <?php endif; ?>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
<script>
function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Claim Code copied to clipboard!');
    });
}
</script>
</body>
</html>
