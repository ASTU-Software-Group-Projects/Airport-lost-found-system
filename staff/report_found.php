<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Found Item - <?= SITE_NAME ?></title>
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
              <a href="dashboard.php" class="glass-nav__item" style="text-decoration: none;">Dashboard</a>
              <a href="view_lost.php" class="glass-nav__item" style="text-decoration: none;">Lost Items</a>
              <a href="view_found.php" class="glass-nav__item" style="text-decoration: none;">Found Items</a>
              <a href="match_items.php" class="glass-nav__item" style="text-decoration: none;">Match Items</a>
              <a href="logout.php" class="glass-nav__item" style="text-decoration: none; color: #f87171;">Logout</a>
            </nav>
        </div>
    <h1 class="hero__title" style="font-size: 2.5rem;">Log Found Item</h1>
  </header>

  <div class="container page" style="max-width: 800px;">
    <div class="glass glass-card">
        <form id="foundForm" action="save_found.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm('foundForm')">
            
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Logged By (Staff)</label>
                    <div class="glass-input-wrap">
                        <input type="text" class="glass-input" value="<?= htmlspecialchars($_SESSION['staff_name']) ?>" disabled style="opacity: 0.7;">
                    </div>
                </div>
            </div>

            <h3 class="glass-card__title" style="font-size: 1.5rem; margin-top: 30px;">1. Item Details</h3>
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Item Name *</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="item_name" class="glass-input" required placeholder="What was found?">
                    </div>
                </div>
            </div>
            <div class="glass-form__row">
                <div>
                    <label class="glass-card__label">Color *</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="item_color" class="glass-input" required placeholder="e.g. Silver">
                    </div>
                </div>
                <div>
                    <label class="glass-card__label">Brand</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="brand" class="glass-input" placeholder="e.g. Samsung">
                    </div>
                </div>
            </div>
            
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Detailed Description *</label>
                    <textarea name="item_description" class="glass-textarea" required placeholder="Describe condition, unique marks..."></textarea>
                </div>
            </div>
            
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Upload Photo</label>
                    <div class="glass-input-wrap">
                        <input type="file" name="photo" class="glass-input" accept="image/jpeg, image/png, image/gif" onchange="previewImage(this, 'photoPreview')" style="padding: 10px;">
                    </div>
                    <img id="photoPreview" src="#" alt="Preview" style="display:none; max-width: 200px; margin-top: 15px; border-radius: var(--radius-md); border: 1px solid var(--glass-border);">
                </div>
            </div>

            <h3 class="glass-card__title" style="font-size: 1.5rem; margin-top: 40px;">2. Recovery Details</h3>
            <div class="glass-form__row">
                <div>
                    <label class="glass-card__label">Found Location *</label>
                    <select name="found_location" class="glass-select" required>
                        <option value="" disabled selected>Select Location</option>
                        <option value="Terminal 1 - Arrivals">Terminal 1 - Arrivals</option>
                        <option value="Terminal 1 - Departures">Terminal 1 - Departures</option>
                        <option value="Terminal 2 - Arrivals">Terminal 2 - Arrivals</option>
                        <option value="Terminal 2 - Departures">Terminal 2 - Departures</option>
                        <option value="Domestic Terminal">Domestic Terminal</option>
                        <option value="Baggage Claim Area">Baggage Claim Area</option>
                        <option value="Security Checkpoint">Security Checkpoint</option>
                        <option value="Duty Free Area">Duty Free Area</option>
                        <option value="Business Lounge">Business Lounge</option>
                        <option value="Parking Area">Parking Area</option>
                        <option value="On-board (Flight)">On-board (Flight)</option>
                        <option value="Restrooms">Restrooms</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="glass-card__label">Date Found *</label>
                    <div class="glass-input-wrap">
                        <input type="date" name="found_date" class="glass-input" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>

            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Storage Location in Office *</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="storage_location" class="glass-input" required placeholder="e.g., Rack 5, Shelf B or Safe Box 1">
                    </div>
                </div>
            </div>

            <div style="margin-top: 40px; display: flex; gap: 20px;">
                <a href="dashboard.php" class="glass glass-btn glass-btn--ghost" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="glass glass-btn glass-btn--primary" style="flex: 2; justify-content: center;">
                    Save Found Item & Check Matches
                </button>
            </div>
        </form>
    </div>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
