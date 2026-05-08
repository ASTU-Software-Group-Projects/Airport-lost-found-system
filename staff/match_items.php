<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

$lost_search = isset($_GET['lost_search']) ? sanitize($_GET['lost_search']) : '';
$found_search = isset($_GET['found_search']) ? sanitize($_GET['found_search']) : '';

// Fetch unmatched lost items
$lost_query = "SELECT * FROM lost_items WHERE status = 'pending'";
if (!empty($lost_search)) {
    $lost_query .= " AND (claim_code LIKE '%$lost_search%' OR item_name LIKE '%$lost_search%')";
}
$lost_query .= " ORDER BY lost_date DESC LIMIT 50";
$lost_result = mysqli_query($conn, $lost_query);

// Fetch unmatched found items
$found_query = "SELECT * FROM found_items WHERE status = 'unclaimed'";
if (!empty($found_search)) {
    $found_query .= " AND (found_code LIKE '%$found_search%' OR item_name LIKE '%$found_search%')";
}
$found_query .= " ORDER BY found_date DESC LIMIT 50";
$found_result = mysqli_query($conn, $found_query);

$success_msg = isset($_SESSION['match_success']) ? $_SESSION['match_success'] : '';
unset($_SESSION['match_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Items - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .item-list { max-height: 500px; overflow-y: auto; padding: 10px; }
        .match-card { 
            border: 1px solid var(--glass-border); 
            border-radius: var(--radius-md); 
            padding: 15px; 
            margin-bottom: 12px; 
            cursor: pointer; 
            transition: all 0.3s var(--ease-glass); 
            background: var(--glass-white);
            backdrop-filter: var(--blur-sm);
        }
        .match-card:hover { 
            transform: translateY(-2px);
            background: var(--glass-white-md);
            border-color: var(--accent-aqua);
        }
        input[type="radio"] { display: none; }
        input[type="radio"]:checked + label .match-card { 
            background: rgba(94, 231, 223, 0.15);
            border-color: var(--accent-aqua); 
            box-shadow: 0 0 15px rgba(94, 231, 223, 0.2), inset 0 0 10px rgba(94, 231, 223, 0.1);
        }
        .item-list::-webkit-scrollbar { width: 6px; }
        .item-list::-webkit-scrollbar-track { background: transparent; }
        .item-list::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }
    </style>
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
              <a href="match_items.php" class="glass-nav__item glass-nav__item--active" style="text-decoration: none;">Match Items</a>
              <a href="logout.php" class="glass-nav__item" style="text-decoration: none; color: #f87171;">Logout</a>
            </nav>
        </div>
    <h1 class="hero__title" style="font-size: 2.5rem;">Manual Item Matching</h1>
  </header>

  <div class="container page">
    <?php if ($success_msg): ?>
        <div class="glass-badge glass-badge--lime" style="width: 100%; justify-content: center; padding: 15px; margin-bottom: 30px; font-size: 1rem;">
            <span class="glass-badge__dot"></span> <?= $success_msg ?>
        </div>
    <?php endif; ?>

    <form action="process_match.php" method="POST" id="matchForm" onsubmit="return validateMatch()">
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            
            <!-- Left Column: Lost Items -->
            <div class="glass glass-card" style="flex: 1; min-width: 350px;">
                <h3 class="glass-card__title" style="font-size: 1.3rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; margin-bottom: 20px;">1. Select Lost Item</h3>
                <div style="margin-bottom: 20px;">
                    <div class="glass-input-wrap">
                        <input type="text" id="filterLost" class="glass-input" placeholder="Filter lost items..." onkeyup="filterDivs('filterLost', 'lostList')">
                        <span class="glass-input-icon">🔍</span>
                    </div>
                </div>
                <div class="item-list" id="lostList">
                    <?php if (mysqli_num_rows($lost_result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($lost_result)): ?>
                            <div class="item-div">
                                <input type="radio" name="lost_id" id="lost_<?= $row['id'] ?>" value="<?= $row['id'] ?>">
                                <label for="lost_<?= $row['id'] ?>" style="display:block; width: 100%;">
                                    <div class="match-card">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="color: var(--accent-aqua); font-size: 0.9rem;"><?= $row['claim_code'] ?></strong>
                                            <span style="font-size: 0.75rem; opacity: 0.6;"><?= date('M d', strtotime($row['lost_date'])) ?></span>
                                        </div>
                                        <div style="font-weight: 600; margin-top: 8px; color: var(--color-text);"><?= htmlspecialchars($row['item_name']) ?></div>
                                        <div style="font-size: 0.8rem; margin-top: 5px; opacity: 0.8;">
                                            Color: <?= htmlspecialchars($row['item_color']) ?> | Loc: <?= htmlspecialchars($row['lost_location']) ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; opacity: 0.6;">No pending lost items.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Found Items -->
            <div class="glass glass-card" style="flex: 1; min-width: 350px;">
                <h3 class="glass-card__title" style="font-size: 1.3rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px; margin-bottom: 20px;">2. Select Found Item</h3>
                <div style="margin-bottom: 20px;">
                    <div class="glass-input-wrap">
                        <input type="text" id="filterFound" class="glass-input" placeholder="Filter found items..." onkeyup="filterDivs('filterFound', 'foundList')">
                        <span class="glass-input-icon">🔍</span>
                    </div>
                </div>
                <div class="item-list" id="foundList">
                    <?php if (mysqli_num_rows($found_result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($found_result)): ?>
                            <div class="item-div">
                                <input type="radio" name="found_id" id="found_<?= $row['id'] ?>" value="<?= $row['id'] ?>">
                                <label for="found_<?= $row['id'] ?>" style="display:block; width: 100%;">
                                    <div class="match-card">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="color: var(--accent-amber); font-size: 0.9rem;"><?= $row['found_code'] ?></strong>
                                            <span style="font-size: 0.75rem; opacity: 0.6;"><?= date('M d', strtotime($row['found_date'])) ?></span>
                                        </div>
                                        <div style="font-weight: 600; margin-top: 8px; color: var(--color-text);"><?= htmlspecialchars($row['item_name']) ?></div>
                                        <div style="font-size: 0.8rem; margin-top: 5px; opacity: 0.8;">
                                            Color: <?= htmlspecialchars($row['item_color']) ?> | Loc: <?= htmlspecialchars($row['found_location']) ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; opacity: 0.6;">No unclaimed found items.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="glass glass-card" style="margin-top: 40px; text-align: center; padding: 40px;">
            <h3 class="glass-card__title">3. Confirm Match</h3>
            <p class="glass-card__body" style="margin-bottom: 30px;">Please select one item from each list above to match them.</p>
            <button type="submit" class="glass glass-btn glass-btn--primary" style="font-size: 1.1rem; padding: 18px 60px;">
                Confirm Match
            </button>
        </div>
    </form>

    <footer class="footer">
      <p class="footer__text">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Staff Portal.</p>
    </footer>
  </div>
</main>

<script src="../script.js"></script>
<script>
function filterDivs(inputId, listId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const list = document.getElementById(listId);
    const divs = list.getElementsByClassName('item-div');

    for (let i = 0; i < divs.length; i++) {
        let textValue = divs[i].textContent || divs[i].innerText;
        if (textValue.toUpperCase().indexOf(filter) > -1) {
            divs[i].style.display = "";
        } else {
            divs[i].style.display = "none";
        }
    }
}

function validateMatch() {
    const lostSelected = document.querySelector('input[name="lost_id"]:checked');
    const foundSelected = document.querySelector('input[name="found_id"]:checked');
    
    if (!lostSelected || !foundSelected) {
        alert("Please select ONE Lost Item and ONE Found Item to match.");
        return false;
    }
    
    return confirm("Are you sure you want to match these items? This action will notify the passenger.");
}
</script>
</body>
</html>
