<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

$q = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$type = isset($_GET['type']) ? sanitize($_GET['type']) : 'both';

$lost_results = null;
$found_results = null;

if (!empty($q)) {
    if ($type == 'both' || $type == 'lost') {
        $query = "SELECT * FROM lost_items 
                  WHERE claim_code LIKE '%$q%' 
                  OR passenger_name LIKE '%$q%' 
                  OR email LIKE '%$q%' 
                  OR phone LIKE '%$q%' 
                  OR item_name LIKE '%$q%' 
                  OR item_description LIKE '%$q%'
                  ORDER BY lost_date DESC";
        $lost_results = mysqli_query($conn, $query);
    }
    
    if ($type == 'both' || $type == 'found') {
        $query = "SELECT * FROM found_items 
                  WHERE found_code LIKE '%$q%' 
                  OR staff_name LIKE '%$q%' 
                  OR item_name LIKE '%$q%' 
                  OR item_description LIKE '%$q%' 
                  OR storage_location LIKE '%$q%'
                  ORDER BY found_date DESC";
        $found_results = mysqli_query($conn, $query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Search - <?= SITE_NAME ?></title>
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
    <div class="nav-container" style="width: 100%; display: flex; justify-content: center; margin-bottom: 40px;">
        <nav class="glass glass-nav" aria-label="Main navigation">
          <a href="dashboard.php" class="glass-nav__item" style="text-decoration: none;">Dashboard</a>
          <a href="view_lost.php" class="glass-nav__item" style="text-decoration: none;">Lost Items</a>
          <a href="view_found.php" class="glass-nav__item" style="text-decoration: none;">Found Items</a>
          <a href="match_items.php" class="glass-nav__item" style="text-decoration: none;">Match Items</a>
          <a href="logout.php" class="glass-nav__item" style="text-decoration: none; color: #f87171;">Logout</a>
        </nav>
    </div>
    <h1 class="hero__title" style="font-size: 2.5rem;">Global Search</h1>
  </header>

  <div class="container page">
    <div class="glass glass-card" style="max-width: 700px; margin: 0 auto 40px;">
        <form action="" method="GET">
            <div style="display: flex; gap: 0; margin-bottom: 20px;">
                <div class="glass-input-wrap" style="flex: 1;">
                    <input type="text" name="q" class="glass-input" placeholder="Enter keywords, name, or code..." value="<?= htmlspecialchars($q) ?>" style="border-radius: var(--radius-md) 0 0 var(--radius-md); font-size: 1.1rem; padding: 18px 20px;">
                </div>
                <button type="submit" class="glass glass-btn glass-btn--primary" style="border-radius: 0 var(--radius-md) var(--radius-md) 0; padding: 0 30px;">Search</button>
            </div>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <label class="glass-check-label">
                    <input type="radio" name="type" value="both" <?= $type == 'both' ? 'checked' : '' ?>>
                    <span class="glass-radio-box"></span>
                    Both
                </label>
                <label class="glass-check-label">
                    <input type="radio" name="type" value="lost" <?= $type == 'lost' ? 'checked' : '' ?>>
                    <span class="glass-radio-box"></span>
                    Lost Items Only
                </label>
                <label class="glass-check-label">
                    <input type="radio" name="type" value="found" <?= $type == 'found' ? 'checked' : '' ?>>
                    <span class="glass-radio-box"></span>
                    Found Items Only
                </label>
            </div>
        </form>
    </div>

    <?php if (!empty($q)): ?>
        <?php 
        $lost_count = $lost_results ? mysqli_num_rows($lost_results) : 0;
        $found_count = $found_results ? mysqli_num_rows($found_results) : 0;
        ?>
        
        <h3 class="glass-card__title" style="font-size: 1.5rem; margin-bottom: 20px;">Search Results for "<?= htmlspecialchars($q) ?>" <span style="font-size: 1rem; opacity: 0.6; font-weight: 300;">(<?= $lost_count + $found_count ?> total)</span></h3>

        <div class="glass-tabs">
            <div class="glass glass-tab-list" role="tablist">
                <?php if ($type == 'both' || $type == 'lost'): ?>
                    <button class="glass-tab <?= ($lost_count > 0 || $type == 'lost') ? 'is-active' : '' ?>" onclick="switchTab(event, 'lostResults')">Lost Items (<?= $lost_count ?>)</button>
                <?php endif; ?>
                <?php if ($type == 'both' || $type == 'found'): ?>
                    <button class="glass-tab <?= ($lost_count == 0 && $type != 'lost') ? 'is-active' : '' ?>" onclick="switchTab(event, 'foundResults')">Found Items (<?= $found_count ?>)</button>
                <?php endif; ?>
            </div>

            <?php if ($type == 'both' || $type == 'lost'): ?>
                <div id="lostResults" class="glass glass-tab-panel <?= ($lost_count > 0 || $type == 'lost') ? 'is-active' : '' ?> glass-table-wrap">
                    <?php if ($lost_count > 0): ?>
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Item</th>
                                    <th>Passenger</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($lost_results)): ?>
                                <tr>
                                    <td><strong style="color: var(--accent-aqua);"><?= $row['claim_code'] ?></strong></td>
                                    <td style="color: var(--color-text);"><?= htmlspecialchars($row['item_name']) ?></td>
                                    <td style="color: var(--color-text);"><?= htmlspecialchars($row['passenger_name']) ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = 'glass-badge--amber';
                                        if($row['status'] == 'matched') $statusClass = 'glass-badge--lime';
                                        if($row['status'] == 'returned') $statusClass = 'glass-badge--violet';
                                        ?>
                                        <span class="glass-badge <?= $statusClass ?>" style="font-size: 0.7rem;"><?= strtoupper($row['status']) ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; color: var(--color-text-muted);">No lost items found matching "<?= htmlspecialchars($q) ?>"</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($type == 'both' || $type == 'found'): ?>
                <div id="foundResults" class="glass glass-tab-panel <?= ($lost_count == 0 && $type != 'lost') ? 'is-active' : '' ?> glass-table-wrap">
                    <?php if ($found_count > 0): ?>
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Item</th>
                                    <th>Storage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($found_results)): ?>
                                <tr>
                                    <td><strong style="color: var(--accent-amber);"><?= $row['found_code'] ?></strong></td>
                                    <td style="color: var(--color-text);"><?= htmlspecialchars($row['item_name']) ?></td>
                                    <td style="color: var(--color-text);"><?= htmlspecialchars($row['storage_location']) ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = 'glass-badge--amber';
                                        if($row['status'] == 'matched') $statusClass = 'glass-badge--lime';
                                        if($row['status'] == 'returned') $statusClass = 'glass-badge--violet';
                                        ?>
                                        <span class="glass-badge <?= $statusClass ?>" style="font-size: 0.7rem;"><?= strtoupper($row['status']) ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; color: var(--color-text-muted);">No found items found matching "<?= htmlspecialchars($q) ?>"</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
<script>
function switchTab(evt, tabId) {
    const tabs = document.getElementsByClassName('glass-tab');
    const contents = document.getElementsByClassName('glass-tab-panel');
    
    for (let i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('is-active');
    }
    for (let i = 0; i < contents.length; i++) {
        contents[i].classList.remove('is-active');
    }
    
    evt.currentTarget.classList.add('is-active');
    document.getElementById(tabId).classList.add('is-active');
}
</script>
</body>
</html>
