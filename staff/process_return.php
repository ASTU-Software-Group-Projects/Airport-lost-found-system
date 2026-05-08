<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: return_item.php");
    exit();
}

$found_id = (int)$_POST['found_id'];
$lost_id = (int)$_POST['lost_id'];
$id_type = sanitize($_POST['id_type']);
$id_number = sanitize($_POST['id_number']);
$receiver_name = sanitize($_POST['receiver_name']);
$signature = sanitize($_POST['signature']);
$notes = sanitize($_POST['notes']);

mysqli_begin_transaction($conn);

try {
    // Update found item status
    $update_found = "UPDATE found_items SET status = 'returned' WHERE id = $found_id";
    if (!mysqli_query($conn, $update_found)) throw new Exception("Error updating found item.");

    // Update lost item status
    $update_lost = "UPDATE lost_items SET status = 'returned' WHERE id = $lost_id";
    if (!mysqli_query($conn, $update_lost)) throw new Exception("Error updating lost item.");

    mysqli_commit($conn);
    $success = true;
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $success = false;
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Processed - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="scene" aria-hidden="true">
  <div class="scene__blob scene__blob--1"></div>
  <div class="scene__blob scene__blob--2"></div>
  <div class="scene__blob scene__blob--3"></div>
</div>

<button class="glass glass-btn theme-toggle-btn" id="theme-toggle" aria-label="Toggle theme">
  <span class="icon-dark"><i class="fas fa-sun"></i></span>
  <span class="icon-light"><i class="fas fa-moon"></i></span>
</button>

<main class="page">
  <header class="hero" style="min-height: auto; padding: 40px 24px 20px;">
    <div class="container">
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
        <h1 class="hero__title" style="font-size: 2.5rem;">Process Completion</h1>
    </div>
  </header>

  <div class="container page" style="max-width: 600px; margin-top: 40px;">
    <div class="glass glass-card cascade" style="text-align: center; padding: 3rem;">
        <?php if ($success): ?>
            <div style="font-size: 5rem; color: var(--accent-aqua); margin-bottom: 25px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="glass-card__title">Success!</h2>
            <p class="glass-card__body" style="margin-bottom: 30px;">
                The item has been successfully handed over to <strong style="color: var(--accent-amber);"><?= htmlspecialchars($receiver_name) ?></strong>.<br>
                The case is now closed and archived.
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <a href="dashboard.php" class="glass glass-btn glass-btn--primary" style="justify-content: center;">
                    Back to Dashboard
                </a>
                <a href="return_item.php" class="glass glass-btn glass-btn--ghost" style="justify-content: center;">
                    Process Another Return
                </a>
            </div>
        <?php else: ?>
            <div style="font-size: 5rem; color: var(--accent-rose); margin-bottom: 25px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="glass-card__title">Process Failed</h2>
            <p class="glass-card__body" style="margin-bottom: 30px;">
                We encountered an error while updating the records:<br>
                <code style="color: var(--accent-rose); background: rgba(238, 28, 35, 0.1); padding: 5px 10px; border-radius: 4px;"><?= $error ?></code>
            </p>
            <a href="return_item.php?found_id=<?= $found_id ?>" class="glass glass-btn glass-btn--primary" style="justify-content: center;">
                Try Again
            </a>
        <?php endif; ?>
    </div>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
