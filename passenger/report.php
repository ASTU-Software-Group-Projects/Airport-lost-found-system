<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Item - <?= SITE_NAME ?></title>
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
            <nav class="glass glass-nav cascade">
              <a href="../index.php" class="glass-nav__item">Home</a>
              <a href="report.php" class="glass-nav__item glass-nav__item--active">Report Lost</a>
              <a href="check_status.php" class="glass-nav__item">Check Status</a>
              <a href="../staff/login.php" class="glass-nav__item">Staff Portal</a>
            </nav>
        </div>
        <h1 class="hero__title" style="font-size: 3rem;">Report Lost Item</h1>
        <p class="glass-card__body" style="max-width: 600px; margin: 0 auto;">Provide as much detail as possible to help our team identify your belongings.</p>
    </div>
  </header>

  <div class="container" style="max-width: 800px; margin-top: 40px;">
    <div class="glass glass-card cascade" style="animation-delay: 0.2s;">
        <form id="reportForm" action="save_report.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm('reportForm')">
            
            <div style="margin-bottom: 40px;">
                <h3 class="glass-card__title" style="font-size: 1.4rem; border-bottom: 1px solid var(--glass-border-subtle); padding-bottom: 10px; margin-bottom: 20px;">1. Contact Information</h3>
                <div class="glass-form__row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="glass-card__label">Full Name *</label>
                        <input type="text" name="passenger_name" class="glass-input" required placeholder="John Doe">
                    </div>
                    <div>
                        <label class="glass-card__label">Phone Number *</label>
                        <input type="tel" name="phone" class="glass-input" required placeholder="+251...">
                    </div>
                </div>
                <div class="glass-form__row" style="margin-top: 20px;">
                    <div>
                        <label class="glass-card__label">Email Address *</label>
                        <input type="email" name="email" class="glass-input" required placeholder="your@email.com">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <h3 class="glass-card__title" style="font-size: 1.4rem; border-bottom: 1px solid var(--glass-border-subtle); padding-bottom: 10px; margin-bottom: 20px;">2. Item Details</h3>
                <div class="glass-form__row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="glass-card__label">Item Category *</label>
                        <select name="item_category" class="glass-select" required>
                            <option value="">Select Category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Bags/Luggage">Bags/Luggage</option>
                            <option value="Documents">Documents</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="glass-card__label">Item Name *</label>
                        <input type="text" name="item_name" class="glass-input" required placeholder="e.g. MacBook Pro">
                    </div>
                </div>
                <div class="glass-form__row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label class="glass-card__label">Color *</label>
                        <input type="text" name="item_color" class="glass-input" required placeholder="e.g. Space Grey">
                    </div>
                    <div>
                        <label class="glass-card__label">Brand</label>
                        <input type="text" name="brand" class="glass-input" placeholder="e.g. Apple">
                    </div>
                </div>
                <div class="glass-form__row" style="margin-top: 20px;">
                    <label class="glass-card__label">Description *</label>
                    <textarea name="item_description" class="glass-textarea" rows="4" required placeholder="Mention any unique marks, serial numbers, or features..."></textarea>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <h3 class="glass-card__title" style="font-size: 1.4rem; border-bottom: 1px solid var(--glass-border-subtle); padding-bottom: 10px; margin-bottom: 20px;">3. Incident Details</h3>
                <div class="glass-form__row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="glass-card__label">Location Lost *</label>
                        <select name="lost_location" class="glass-select" required>
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
                        <label class="glass-card__label">Date Lost *</label>
                        <input type="date" name="lost_date" class="glass-input" required>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                <h3 class="glass-card__title" style="font-size: 1.4rem; border-bottom: 1px solid var(--glass-border-subtle); padding-bottom: 10px; margin-bottom: 20px;">4. Verification</h3>
                <label class="glass-card__label">Upload Photo (Optional)</label>
                <div class="glass-input-wrap">
                    <input type="file" name="photo" class="glass-input" accept="image/*" style="padding-top: 10px;">
                    <span class="glass-input-icon"><i class="fas fa-camera"></i></span>
                </div>
            </div>

            <button type="submit" class="glass glass-btn glass-btn--primary" style="width: 100%; justify-content: center; padding: 20px; font-size: 1.2rem;">
                Submit Report
            </button>
        </form>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
        <p class="footer__text">&copy; <?= date('Y') ?> Ethiopian Airlines. Safe Travels.</p>
    </div>
  </footer>
</main>

<script src="../script.js"></script>
</body>
</html>
