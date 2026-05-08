<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch dashboard stats
$stats = ['total_lost' => 0, 'total_found' => 0, 'matched' => 0, 'returned' => 0];
$queries = [
    'total_lost' => "SELECT COUNT(*) as count FROM lost_items",
    'total_found' => "SELECT COUNT(*) as count FROM found_items",
    'matched' => "SELECT COUNT(*) as count FROM lost_items WHERE status = 'matched'",
    'returned' => "SELECT COUNT(*) as count FROM lost_items WHERE status = 'returned'"
];

foreach ($queries as $key => $query) {
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats[$key] = $row['count'];
    }
}

// Fetch recent lost items
$recent_lost = mysqli_query($conn, "SELECT * FROM lost_items ORDER BY reported_at DESC LIMIT 5");

// Fetch recent found items
$recent_found = mysqli_query($conn, "SELECT * FROM found_items ORDER BY found_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - <?= SITE_NAME ?></title>
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
              <a href="dashboard.php" class="glass-nav__item glass-nav__item--active">Dashboard</a>
              <a href="view_lost.php" class="glass-nav__item">Lost Items</a>
              <a href="view_found.php" class="glass-nav__item">Found Items</a>
              <a href="match_items.php" class="glass-nav__item">Matches</a>
              <a href="logout.php" class="glass-nav__item" style="color: var(--accent-rose);">Logout</a>
            </nav>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
            <div class="cascade" style="animation-delay: 0.1s;">
                <h1 class="hero__title" style="font-size: 2.5rem; margin-bottom: 5px; text-align: left;">Staff Dashboard</h1>
                <p class="glass-card__body">Welcome back, <span style="color: var(--accent-amber); font-weight: 600;"><?= htmlspecialchars($_SESSION['staff_name']) ?></span></p>
            </div>
            <div class="cascade" style="animation-delay: 0.2s; flex: 1; max-width: 400px;">
                <form action="search.php" method="GET" style="display: flex; gap: 10px;">
                    <div class="glass-input-wrap" style="flex: 1;">
                        <input type="text" name="q" class="glass-input" placeholder="Search code, item, name...">
                    </div>
                    <button type="submit" class="glass glass-btn glass-btn--primary">Search</button>
                </form>
            </div>
        </div>
    </div>
  </header>

  <div class="container">
    <div class="stats cascade" style="animation-delay: 0.3s; margin-bottom: 40px;">
      <div class="glass stats__item" style="border-bottom: 4px solid var(--accent-aqua);">
        <div class="stats__num" style="color: var(--accent-aqua);"><?= $stats['total_lost'] ?></div>
        <div class="stats__desc">Total Lost</div>
      </div>
      <div class="glass stats__item" style="border-bottom: 4px solid var(--accent-amber);">
        <div class="stats__num"><?= $stats['total_found'] ?></div>
        <div class="stats__desc">Total Found</div>
      </div>
      <div class="glass stats__item" style="border-bottom: 4px solid var(--accent-lime);">
        <div class="stats__num" style="color: #fff176;"><?= $stats['matched'] ?></div>
        <div class="stats__desc">Matched</div>
      </div>
      <div class="glass stats__item" style="border-bottom: 4px solid var(--accent-rose);">
        <div class="stats__num" style="color: var(--accent-rose);"><?= $stats['returned'] ?></div>
        <div class="stats__desc">Returned</div>
      </div>
    </div>

    <div class="dashboard-grid">
        <div class="glass glass-card cascade" style="animation-delay: 0.4s;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 class="glass-card__title" style="margin-bottom: 0; font-size: 1.4rem;">Quick Actions</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <a href="report_found.php" class="glass glass-btn" style="padding: 20px; justify-content: center; flex-direction: column; gap: 10px;">
                    <i class="fas fa-plus-circle" style="font-size: 1.5rem; color: var(--accent-aqua);"></i>
                    <span>Log Found Item</span>
                </a>
                <a href="match_items.php" class="glass glass-btn" style="padding: 20px; justify-content: center; flex-direction: column; gap: 10px;">
                    <i class="fas fa-magic" style="font-size: 1.5rem; color: var(--accent-amber);"></i>
                    <span>Run Matcher</span>
                </a>
                <a href="return_item.php" class="glass glass-btn" style="padding: 20px; justify-content: center; flex-direction: column; gap: 10px;">
                    <i class="fas fa-hand-holding-heart" style="font-size: 1.5rem; color: var(--accent-rose);"></i>
                    <span>Process Return</span>
                </a>
                <a href="search.php" class="glass glass-btn" style="padding: 20px; justify-content: center; flex-direction: column; gap: 10px;">
                    <i class="fas fa-search" style="font-size: 1.5rem; opacity: 0.7;"></i>
                    <span>Search Database</span>
                </a>
            </div>
        </div>

        <div class="glass glass-card cascade" style="animation-delay: 0.5s;">
            <h3 class="glass-card__title" style="font-size: 1.4rem;">Recent Reports</h3>
            <div class="glass-table-wrap" style="margin-top: 0;">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Code</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($recent_lost)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><code style="color: var(--accent-aqua);"><?= $row['claim_code'] ?></code></td>
                            <td>
                                <?php 
                                $badge = 'glass-badge--amber';
                                if($row['status'] == 'matched') $badge = 'glass-badge--lime';
                                if($row['status'] == 'returned') $badge = 'glass-badge--violet';
                                ?>
                                <span class="glass-badge <?= $badge ?>" style="font-size: 0.6rem;"><?= $row['status'] ?></span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <a href="view_lost.php" style="color: var(--accent-amber); text-decoration: none; font-size: 0.85rem; font-weight: 600;">View all reports →</a>
            </div>
        </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
        <p class="footer__text">&copy; <?= date('Y') ?> Ethiopian Airlines Staff Portal. Internal Use Only.</p>
    </div>
  </footer>
</main>

<script src="../script.js"></script>
</body>
</html>
