<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Status - <?= SITE_NAME ?></title>
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
  </header>

  <div class="container page" style="max-width: 600px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 50vh;">
    <div class="glass glass-card" style="text-align: center; width: 100%;">
        <div style="font-size: 3.5rem; margin-bottom: 20px; opacity: 0.8;"><i class="fas fa-search"></i></div>
        <h2 class="glass-card__title">Check Item Status</h2>
        <p class="glass-card__body" style="margin-bottom: 30px;">Enter your unique claim code to check if your item has been found.</p>
        
        <form id="statusForm" action="view_status.php" method="GET" onsubmit="return validateForm('statusForm')">
            <div class="glass-form__row glass-form__row--full">
                <div class="glass-input-wrap">
                    <input type="text" name="code" class="glass-input" style="font-size: 1.3rem; text-align: center; text-transform: uppercase; padding: 18px;" placeholder="e.g. LOST-A1B2C3" required>
                </div>
            </div>
            <div style="margin-top: 25px;">
                <button type="submit" class="glass glass-btn glass-btn--primary" style="width: 100%; justify-content: center; padding: 18px; font-size: 1.1rem;">
                    Check Status Now
                </button>
            </div>
        </form>
        
        <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
            <p class="glass-card__body">Don't have a code? <a href="report.php" style="color: var(--accent-aqua); font-weight: 500;">Report Lost Item</a></p>
        </div>
    </div>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
