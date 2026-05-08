<?php
require_once '../config.php';

$code = isset($_GET['code']) ? sanitize($_GET['code']) : '';
$error = '';
$lost_item = null;
$found_item = null;

if (!empty($code)) {
    $query = "SELECT * FROM lost_items WHERE claim_code = '$code'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $lost_item = mysqli_fetch_assoc($result);
        
        // If matched or returned, get the found item details
        if ($lost_item['status'] == 'matched' || $lost_item['status'] == 'returned') {
            $found_query = "SELECT * FROM found_items WHERE matched_to = " . $lost_item['id'];
            $found_result = mysqli_query($conn, $found_query);
            if ($found_result && mysqli_num_rows($found_result) > 0) {
                $found_item = mysqli_fetch_assoc($found_result);
            }
        }
    } else {
        $error = "No report found with this claim code. Please check and try again.";
    }
} else {
    header("Location: check_status.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Details - <?= SITE_NAME ?></title>
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
            <nav class="glass glass-nav" aria-label="Main navigation">
              <a href="../index.php" class="glass-nav__item" style="text-decoration: none;">Home</a>
              <a href="report.php" class="glass-nav__item" style="text-decoration: none;">Report Lost</a>
              <a href="check_status.php" class="glass-nav__item glass-nav__item--active" style="text-decoration: none;">Check Status</a>
              <a href="../staff/login.php" class="glass-nav__item" style="text-decoration: none;">Staff Panel</a>
            </nav>
        </div>
    <h1 class="hero__title" style="font-size: 2.5rem;">Report Status</h1>
  </header>

  <div class="container page">
    <?php if ($error): ?>
        <div class="glass glass-card" style="text-align: center; max-width: 600px; margin: 0 auto;">
            <div style="font-size: 4rem; color: #f87171; margin-bottom: 20px;"><i class="fas fa-exclamation-circle"></i></div>
            <h2 class="glass-card__title" style="color: #f87171;">Not Found</h2>
            <p class="glass-card__body"><?= $error ?></p>
            <div style="margin-top: 30px;">
                <a href="check_status.php" class="glass glass-btn glass-btn--primary">Try Again</a>
            </div>
        </div>
    <?php elseif ($lost_item): ?>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px;">
            <h2 class="glass-card__title" style="margin-bottom: 0;">Status for: <span style="color: var(--accent-aqua);"><?= htmlspecialchars($code) ?></span></h2>
            <div>
                <?php 
                $statusClass = 'glass-badge--amber';
                if($lost_item['status'] == 'matched') $statusClass = 'glass-badge--lime';
                if($lost_item['status'] == 'returned') $statusClass = 'glass-badge--violet';
                ?>
                <span class="glass-badge <?= $statusClass ?>"><span class="glass-badge__dot"></span><?= strtoupper($lost_item['status']) ?></span>
            </div>
        </div>

        <?php if ($lost_item['status'] == 'pending'): ?>
            <div class="glass glass-card" style="text-align: center; padding: 40px; margin-bottom: 30px; border-color: rgba(255, 210, 127, 0.4);">
                <div style="font-size: 3.5rem; margin-bottom: 20px; color: var(--accent-amber);"><i class="fas fa-search"></i></div>
                <h3 class="glass-card__title">Searching for your item</h3>
                <p class="glass-card__body">Our staff is currently looking for your item. We will update this status once a match is found. Please check back later.</p>
            </div>
        <?php elseif ($lost_item['status'] == 'matched'): ?>
            <div class="glass glass-card" style="text-align: center; padding: 40px; margin-bottom: 30px; border-color: rgba(168, 240, 138, 0.4);">
                <div style="font-size: 3.5rem; margin-bottom: 20px;">🎉</div>
                <h3 class="glass-card__title">Item Found!</h3>
                <p class="glass-card__body">Good news! We have found an item that matches your report. Please visit the Lost & Found office to claim it.</p>
                <div style="margin-top: 30px;">
                    <a href="claim_item.php?code=<?= $code ?>" class="glass glass-btn glass-btn--primary" style="font-size: 1.1rem; padding: 15px 40px;">Claim My Item Now</a>
                </div>
            </div>
        <?php elseif ($lost_item['status'] == 'returned'): ?>
            <div class="glass glass-card" style="text-align: center; padding: 40px; margin-bottom: 30px; border-color: rgba(180, 144, 245, 0.4);">
                <div style="font-size: 3.5rem; margin-bottom: 20px; color: var(--accent-violet);"><i class="fas fa-box-open"></i></div>
                <h3 class="glass-card__title">Item Successfully Returned</h3>
                <p class="glass-card__body">This item has been successfully returned to its owner. Case closed. Thank you for using our service.</p>
            </div>
        <?php endif; ?>

        <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="glass glass-card">
                <h3 class="glass-card__title" style="font-size: 1.3rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; margin-bottom: 20px;">Your Report Details</h3>
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                    <tr><td class="glass-card__label" style="width: 40%; margin-bottom: 0;">Item Name</td><td><strong style="color: var(--color-text);"><?= htmlspecialchars($lost_item['item_name']) ?></strong></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Date Lost</td><td><span style="color: var(--color-text);"><?= date('M d, Y', strtotime($lost_item['lost_date'])) ?></span></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Location Lost</td><td><span style="color: var(--color-text);"><?= htmlspecialchars($lost_item['lost_location']) ?></span></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Color/Brand</td><td><span style="color: var(--color-text);"><?= htmlspecialchars($lost_item['item_color']) ?> / <?= htmlspecialchars($lost_item['brand']) ?></span></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Description</td><td><span style="color: var(--color-text);"><?= nl2br(htmlspecialchars($lost_item['item_description'])) ?></span></td></tr>
                </table>
            </div>

            <?php if ($found_item): ?>
            <div class="glass glass-card">
                <h3 class="glass-card__title" style="font-size: 1.3rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; margin-bottom: 20px;">Found Item Details</h3>
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                    <tr><td class="glass-card__label" style="width: 40%; margin-bottom: 0;">Item Name</td><td><strong style="color: var(--color-text);"><?= htmlspecialchars($found_item['item_name']) ?></strong></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Date Found</td><td><span style="color: var(--color-text);"><?= date('M d, Y', strtotime($found_item['found_date'])) ?></span></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Location Found</td><td><span style="color: var(--color-text);"><?= htmlspecialchars($found_item['found_location']) ?></span></td></tr>
                    <?php if ($lost_item['status'] == 'matched'): ?>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Collection Point</td><td><strong style="color: var(--accent-aqua);">Main Terminal Office (C3)</strong></td></tr>
                    <tr><td class="glass-card__label" style="margin-bottom: 0;">Office Hours</td><td><span style="color: var(--color-text);">08:00 AM - 10:00 PM</span></td></tr>
                    <?php endif; ?>
                </table>
                <?php if ($lost_item['status'] == 'matched'): ?>
                    <p class="glass-card__body" style="font-size: 0.85rem; margin-top: 20px; font-style: italic; opacity: 0.7;">* Please bring valid ID proof when collecting your item.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
