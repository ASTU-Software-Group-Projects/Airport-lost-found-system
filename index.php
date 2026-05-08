<?php
require_once 'config.php';

// Get stats for the hero section
$total_lost = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM lost_items"))['count'];
$total_found = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM found_items"))['count'];
$total_returned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM lost_items WHERE status='returned'"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport Lost & Found - Ethiopian Airlines</title>
    <link rel="stylesheet" href="style.css">
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
  <header class="hero">
    <div class="container" style="display: flex; flex-direction: column; align-items: center; gap: 40px;">
        <div class="logo-box cascade" style="margin-bottom: -10px;">
          <img src="logo.png" alt="Ethiopian Airlines Logo" style="height: 60px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));">
        </div>

        <nav class="glass glass-nav cascade">
          <a href="index.php" class="glass-nav__item glass-nav__item--active">Home</a>
          <a href="passenger/report.php" class="glass-nav__item">Report Lost</a>
          <a href="passenger/check_status.php" class="glass-nav__item">Check Status</a>
          <a href="staff/login.php" class="glass-nav__item">Staff Portal</a>
        </nav>

        <div class="hero__kicker glass-badge glass-badge--aqua cascade" style="animation-delay: 0.1s;">
          <span class="glass-badge__dot"></span> Ethiopian Airlines Official Portal
        </div>
        
        <h1 class="hero__title cascade" style="animation-delay: 0.2s;">
          Lost it? <span style="color: var(--accent-amber);">We'll find it.</span>
        </h1>
        
        <p class="hero__sub cascade" style="max-width: 600px; animation-delay: 0.3s; font-size: 1.25rem;">
          Our state-of-the-art management system helps reconnect you with your belongings quickly and securely.
        </p>
        
        <div class="hero__cta cascade" style="animation-delay: 0.4s;">
          <a href="passenger/report.php" class="glass glass-btn glass-btn--primary" style="padding: 14px 28px; font-size: 0.95rem;">
            <i class="fas fa-plus-circle"></i> Report Lost Item
          </a>
          <a href="passenger/check_status.php" class="glass glass-btn glass-btn--ghost" style="padding: 14px 28px; font-size: 0.95rem;">
            <i class="fas fa-search"></i> Check Claim Status
          </a>
        </div>

        <div class="stats cascade" style="animation-delay: 0.5s;">
          <div class="glass stats__item">
            <div class="stats__num"><?= $total_lost ?></div>
            <div class="stats__desc">Items Lost</div>
          </div>
          <div class="glass stats__item">
            <div class="stats__num"><?= $total_found ?></div>
            <div class="stats__desc">Items Found</div>
          </div>
          <div class="glass stats__item">
            <div class="stats__num"><?= $total_returned ?></div>
            <div class="stats__desc">Returned Home</div>
          </div>
        </div>
    </div>
  </header>

  <section class="container" style="padding: 100px 0;">
    <div class="glass glass-card cascade" style="animation-delay: 0.6s;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px; padding: 20px;">
        <div>
          <h2 class="glass-card__title" style="font-size: 2rem;">How it works</h2>
          <p class="glass-card__body">Our streamlined process ensures your claims are handled with priority and precision.</p>
          <div style="margin-top: 30px;">
              <a href="staff/login.php" style="color: var(--accent-amber); text-decoration: none; font-weight: 600;">Airport Staff? Login Here →</a>
          </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 30px;">
          <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div class="glass-badge glass-badge--aqua" style="width: 40px; height: 40px; border-radius: 50%; justify-content: center; flex-shrink: 0; font-size: 1rem;">1</div>
            <div>
              <h4 style="margin-bottom: 8px; font-size: 1.2rem;">Submit a Report</h4>
              <p class="glass-card__body" style="font-size: 0.95rem;">Provide details and photos of your lost item. Get a unique claim code instantly.</p>
            </div>
          </div>
          <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div class="glass-badge glass-badge--amber" style="width: 40px; height: 40px; border-radius: 50%; justify-content: center; flex-shrink: 0; font-size: 1rem;">2</div>
            <div>
              <h4 style="margin-bottom: 8px; font-size: 1.2rem;">We Search</h4>
              <p class="glass-card__body" style="font-size: 0.95rem;">Our staff matches reports against found inventory using smart algorithms.</p>
            </div>
          </div>
          <div style="display: flex; gap: 20px; align-items: flex-start;">
            <div class="glass-badge glass-badge--lime" style="width: 40px; height: 40px; border-radius: 50%; justify-content: center; flex-shrink: 0; font-size: 1rem;">3</div>
            <div>
              <h4 style="margin-bottom: 8px; font-size: 1.2rem;">Collect Item</h4>
              <p class="glass-card__body" style="font-size: 0.95rem;">Once matched, visit our terminal office with your ID to collect your item.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
        <p class="footer__text">&copy; <?= date('Y') ?> Ethiopian Airlines Airport Lost & Found. All rights reserved.</p>
    </div>
  </footer>
</main>

<script src="script.js"></script>
</body>
</html>
