<?php
require_once '../config.php';
if (isStaffLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}
$error = isset($_GET['error']) ? 'Invalid username or password' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">

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

<div class="glass glass-card" style="width: 100%; max-width: 400px; padding: 2.5rem; text-align: center;">
    <div class="mb-4">
        <div class="logo-box cascade" style="margin-bottom: 25px;">
            <img src="../logo.png" alt="Ethiopian Airlines Logo" style="height: 60px;">
        </div>
        <h2 class="glass-card__title">Staff Portal</h2>
        <p class="glass-card__body">Lost & Found Management System</p>
    </div>

    <?php if ($error): ?>
        <div class="glass-badge glass-badge--error" style="width: 100%; justify-content: center; padding: 10px; margin-bottom: 20px;">
            <span class="glass-badge__dot"></span> <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="authenticate.php" method="POST" style="text-align: left;">
        <div class="glass-form__row glass-form__row--full">
            <div>
                <label class="glass-card__label">Username</label>
                <div class="glass-input-wrap">
                    <input type="text" name="username" class="glass-input" style="padding-left: 45px;" required placeholder="Enter username">
                    <span class="glass-input-icon" style="left: 15px; right: auto;"><i class="fas fa-user"></i></span>
                </div>
            </div>
        </div>
        
        <div class="glass-form__row glass-form__row--full">
            <div>
                <label class="glass-card__label">Password</label>
                <div class="glass-input-wrap">
                    <input type="password" name="password" class="glass-input" style="padding-left: 45px;" required placeholder="••••••••">
                    <span class="glass-input-icon" style="left: 15px; right: auto;"><i class="fas fa-lock"></i></span>
                </div>
            </div>
        </div>
        
        <div style="margin: 15px 0;">
            <label class="glass-check-label">
                <input type="checkbox" name="remember">
                <span class="glass-check-box"></span>
                Remember Me
            </label>
        </div>

        <button type="submit" class="glass glass-btn glass-btn--primary" style="width: 100%; justify-content: center; padding: 15px; margin-top: 10px;">
            Login
        </button>
        
        <div style="text-align: center; margin-top: 25px;">
            <p class="glass-card__body" style="font-size: 0.85rem; opacity: 0.7;">Hint: Demo credentials: staff / staff123</p>
            <div style="margin-top: 15px;">
                <a href="../index.php" style="color: var(--accent-aqua); text-decoration: none; font-size: 0.9rem;">← Back to Home</a>
            </div>
        </div>
    </form>
</div>

<script src="../script.js"></script>
</body>
</html>
