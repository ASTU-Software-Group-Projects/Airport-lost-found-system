<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$query = "SELECT * FROM found_items WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (found_code LIKE '%$search%' OR item_name LIKE '%$search%' OR found_location LIKE '%$search%' OR storage_location LIKE '%$search%')";
}
if (!empty($status_filter)) {
    $query .= " AND status = '$status_filter'";
}

$query .= " ORDER BY found_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Found Items - <?= SITE_NAME ?></title>
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
              <a href="view_found.php" class="glass-nav__item glass-nav__item--active" style="text-decoration: none;">Found Items</a>
              <a href="match_items.php" class="glass-nav__item" style="text-decoration: none;">Match Items</a>
              <a href="logout.php" class="glass-nav__item" style="text-decoration: none; color: #f87171;">Logout</a>
            </nav>
        </div>
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <h1 class="hero__title" style="font-size: 2.5rem; margin-bottom: 0;">Found Items Database</h1>
        <a href="report_found.php" class="glass glass-btn glass-btn--primary"><i class="fas fa-plus"></i> Log New Item</a>
    </div>
  </header>

  <div class="container page">
    <div class="glass glass-card" style="margin-bottom: 30px;">
        <form action="" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 2; min-width: 200px;">
                <label class="glass-card__label">Search</label>
                <div class="glass-input-wrap">
                    <input type="text" name="search" id="searchInput" class="glass-input" placeholder="Code, item, location..." value="<?= htmlspecialchars($search) ?>" onkeyup="filterTable('searchInput', 'foundTable')">
                    <span class="glass-input-icon">🔍</span>
                </div>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label class="glass-card__label">Status</label>
                <select name="status" class="glass-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="unclaimed" <?= $status_filter == 'unclaimed' ? 'selected' : '' ?>>Unclaimed</option>
                    <option value="matched" <?= $status_filter == 'matched' ? 'selected' : '' ?>>Matched</option>
                    <option value="returned" <?= $status_filter == 'returned' ? 'selected' : '' ?>>Returned</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="glass glass-btn glass-btn--primary">Filter</button>
                <a href="view_found.php" class="glass glass-btn glass-btn--ghost">Reset</a>
            </div>
        </form>
    </div>

    <div class="glass glass-table-wrap">
        <table class="glass-table" id="foundTable">
            <thead>
                <tr>
                    <th>Found Code</th>
                    <th>Item Name</th>
                    <th>Location Found</th>
                    <th>Storage</th>
                    <th>Date Found</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong style="color: var(--accent-amber);"><?= $row['found_code'] ?></strong></td>
                        <td>
                            <span style="color: var(--color-text); font-weight: 500;"><?= htmlspecialchars($row['item_name']) ?></span><br>
                            <span style="font-size: 0.75rem; opacity: 0.6;"><?= htmlspecialchars($row['item_color']) ?></span>
                        </td>
                        <td style="color: var(--color-text);"><?= htmlspecialchars($row['found_location']) ?></td>
                        <td style="color: var(--color-text);"><?= htmlspecialchars($row['storage_location']) ?></td>
                        <td style="color: var(--color-text);"><?= date('M d, Y', strtotime($row['found_date'])) ?></td>
                        <td>
                            <?php 
                            $statusClass = 'glass-badge--amber';
                            if($row['status'] == 'matched') $statusClass = 'glass-badge--lime';
                            if($row['status'] == 'returned') $statusClass = 'glass-badge--violet';
                            ?>
                            <span class="glass-badge <?= $statusClass ?>" style="font-size: 0.7rem;"><?= strtoupper($row['status']) ?></span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <?php if($row['status'] == 'unclaimed'): ?>
                                    <a href="match_items.php?found_search=<?= $row['found_code'] ?>" class="glass glass-btn glass-btn--primary glass-btn--sm" style="padding: 4px 10px;">Match</a>
                                <?php elseif($row['status'] == 'matched'): ?>
                                    <a href="return_item.php?found_id=<?= $row['id'] ?>" class="glass glass-btn glass-btn--accent glass-btn--sm" style="padding: 4px 10px;">Return</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--color-text-muted);">No found items matching your criteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
</body>
</html>
