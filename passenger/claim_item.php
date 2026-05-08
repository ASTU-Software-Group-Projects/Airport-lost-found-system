<?php
require_once '../config.php';

$code = isset($_GET['code']) ? sanitize($_GET['code']) : '';
$lost_item = null;

if (!empty($code)) {
    $query = "SELECT * FROM lost_items WHERE claim_code = '$code' AND status = 'matched'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $lost_item = mysqli_fetch_assoc($result);
    } else {
        die("Invalid claim code or item is not ready for claim.");
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
    <title>Claim Item - <?= SITE_NAME ?></title>
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
  <span class="icon-dark" aria-hidden="true">☀️</span>
  <span class="icon-light" aria-hidden="true">🌙</span>
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
    <h1 class="hero__title" style="font-size: 2.5rem;">Item Claim Form</h1>
  </header>

  <div class="container page" style="max-width: 600px;">
    <div class="glass glass-card">
        <p class="glass-card__body" style="margin-bottom: 30px; text-align: center;">Please fill this form before visiting the Lost & Found office to speed up the process.</p>
        
        <div class="glass-badge glass-badge--aqua" style="width: 100%; justify-content: center; padding: 15px; margin-bottom: 30px; font-size: 0.9rem;">
            <span class="glass-badge__dot"></span> <strong>Code:</strong> <?= htmlspecialchars($code) ?> | <strong>Item:</strong> <?= htmlspecialchars($lost_item['item_name']) ?>
        </div>

        <form action="process_claim_passenger.php" method="POST" id="claimForm" onsubmit="alert('Claim information saved! Please visit the office with your ID proof.'); return false;">
            <input type="hidden" name="claim_code" value="<?= htmlspecialchars($code) ?>">
            
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Full Name</label>
                    <div class="glass-input-wrap">
                        <input type="text" class="glass-input" value="<?= htmlspecialchars($lost_item['passenger_name']) ?>" disabled style="opacity: 0.7;">
                    </div>
                </div>
            </div>
            
            <div class="glass-form__row">
                <div>
                    <label class="glass-card__label">ID Proof Type *</label>
                    <select name="id_type" class="glass-select" required>
                        <option value="">Select ID Type</option>
                        <option value="Driver's License">Driver's License</option>
                        <option value="Passport">Passport</option>
                        <option value="National ID">National ID</option>
                        <option value="Other">Other Official ID</option>
                    </select>
                </div>
                <div>
                    <label class="glass-card__label">ID Proof Number *</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="id_number" class="glass-input" required placeholder="e.g. A1234567">
                    </div>
                </div>
            </div>
            
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Owner Signature (Type Full Name) *</label>
                    <div class="glass-input-wrap">
                        <input type="text" name="signature" class="glass-input" required placeholder="Type your full name as signature">
                    </div>
                </div>
            </div>

            <div style="margin-top: 40px;">
                <button type="submit" class="glass glass-btn glass-btn--primary" style="width: 100%; justify-content: center; padding: 18px; font-size: 1.1rem;">
                    Generate Claim Pass
                </button>
            </div>
        </form>
    </div>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
