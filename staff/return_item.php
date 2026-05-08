<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

$found_id = isset($_GET['found_id']) ? (int)$_GET['found_id'] : 0;
$found_item = null;
$lost_item = null;

if ($found_id > 0) {
    $query = "SELECT f.*, l.claim_code, l.passenger_name, l.phone, l.email, l.id as lost_item_id 
              FROM found_items f 
              JOIN lost_items l ON f.matched_to = l.id 
              WHERE f.id = $found_id AND f.status = 'matched'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $found_item = $data;
    }
}

// Get all matched items for dropdown if no specific ID provided
$matched_items_query = "SELECT f.id, f.found_code, l.claim_code, l.passenger_name, f.item_name 
                        FROM found_items f 
                        JOIN lost_items l ON f.matched_to = l.id 
                        WHERE f.status = 'matched'";
$matched_items = mysqli_query($conn, $matched_items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Return - <?= SITE_NAME ?></title>
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
    <h1 class="hero__title" style="font-size: 2.5rem;">Process Item Return</h1>
  </header>

  <div class="container page" style="max-width: 800px;">
    <div class="glass glass-card">
        <?php if (!$found_item): ?>
            <div class="glass-form__row glass-form__row--full">
                <div>
                    <label class="glass-card__label">Select Matched Item to Return</label>
                    <select class="glass-select" onchange="if(this.value) window.location.href='return_item.php?found_id='+this.value">
                        <option value="">-- Select an item --</option>
                        <?php while($row = mysqli_fetch_assoc($matched_items)): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['found_code'] ?> / <?= $row['claim_code'] ?> - <?= htmlspecialchars($row['item_name']) ?> (<?= htmlspecialchars($row['passenger_name']) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <?php if(mysqli_num_rows($matched_items) == 0): ?>
                <div class="glass-badge glass-badge--violet" style="width: 100%; justify-content: center; padding: 15px; margin-top: 20px;">
                    <span class="glass-badge__dot"></span> No items currently matched and waiting for return.
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="glass-badge glass-badge--aqua" style="width: 100%; justify-content: center; padding: 12px; margin-bottom: 30px; font-size: 0.9rem;">
                <span class="glass-badge__dot"></span> Processing return for <strong><?= htmlspecialchars($found_item['item_name']) ?></strong> to <strong><?= htmlspecialchars($found_item['passenger_name']) ?></strong>.
            </div>

            <form action="process_return.php" method="POST" id="returnForm" onsubmit="return validateForm('returnForm')">
                <input type="hidden" name="found_id" value="<?= $found_id ?>">
                <input type="hidden" name="lost_id" value="<?= $found_item['lost_item_id'] ?>">
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                    <div class="glass glass--dark" style="flex: 1; min-width: 250px; padding: 15px; border-radius: var(--radius-md);">
                        <h4 class="glass-card__label" style="opacity: 0.6;">Lost Report</h4>
                        <p style="color: var(--color-text); margin-top: 5px;"><strong>Code:</strong> <?= $found_item['claim_code'] ?></p>
                        <p style="color: var(--color-text);"><strong>Owner:</strong> <?= htmlspecialchars($found_item['passenger_name']) ?></p>
                        <p style="color: var(--color-text);"><strong>Contact:</strong> <?= htmlspecialchars($found_item['phone']) ?></p>
                    </div>
                    <div class="glass glass--dark" style="flex: 1; min-width: 250px; padding: 15px; border-radius: var(--radius-md);">
                        <h4 class="glass-card__label" style="opacity: 0.6;">Found Record</h4>
                        <p style="color: var(--color-text); margin-top: 5px;"><strong>Code:</strong> <?= $found_item['found_code'] ?></p>
                        <p style="color: var(--color-text);"><strong>Storage:</strong> <?= htmlspecialchars($found_item['storage_location']) ?></p>
                    </div>
                </div>

                <h3 class="glass-card__title" style="font-size: 1.5rem; margin-top: 40px;">Verification Details</h3>
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
                            <input type="text" name="id_number" class="glass-input" required placeholder="Enter ID number">
                        </div>
                    </div>
                </div>
                
                <div class="glass-form__row">
                    <div>
                        <label class="glass-card__label">Receiver Name *</label>
                        <div class="glass-input-wrap">
                            <input type="text" name="receiver_name" class="glass-input" value="<?= htmlspecialchars($found_item['passenger_name']) ?>" required>
                        </div>
                    </div>
                    <div>
                        <label class="glass-card__label">Staff Processing</label>
                        <div class="glass-input-wrap">
                            <input type="text" class="glass-input" value="<?= htmlspecialchars($_SESSION['staff_name']) ?>" disabled style="opacity: 0.6;">
                        </div>
                    </div>
                </div>

                <div class="glass-form__row glass-form__row--full">
                    <div>
                        <label class="glass-card__label">Receiver Signature (Type Full Name) *</label>
                        <div class="glass-input-wrap">
                            <input type="text" name="signature" class="glass-input" required placeholder="I confirm receipt of the item">
                        </div>
                    </div>
                </div>
                
                <div class="glass-form__row glass-form__row--full">
                    <div>
                        <label class="glass-card__label">Additional Notes</label>
                        <textarea name="notes" class="glass-textarea" placeholder="Any comments regarding condition upon return..."></textarea>
                    </div>
                </div>

                <div style="margin-top: 40px; display: flex; gap: 20px;">
                    <a href="return_item.php" class="glass glass-btn glass-btn--ghost" style="flex: 1; justify-content: center; text-decoration: none;">Cancel</a>
                    <button type="submit" class="glass glass-btn glass-btn--accent" style="flex: 2; justify-content: center;">
                        Confirm Handover & Close Case
                    </button>
                </div>
            </form>
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
