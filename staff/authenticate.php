<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$username = sanitize($_POST['username']);
$password = md5($_POST['password']); // Using MD5 as specified in SQL

$query = "SELECT * FROM staff_users WHERE username = '$username' AND password = '$password' AND is_active = 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    $_SESSION['staff_logged_in'] = true;
    $_SESSION['staff_id'] = $user['id'];
    $_SESSION['staff_username'] = $user['username'];
    $_SESSION['staff_name'] = $user['full_name'];
    $_SESSION['staff_role'] = $user['role'];
    
    // Update last login
    $update_query = "UPDATE staff_users SET last_login = NOW() WHERE id = " . $user['id'];
    mysqli_query($conn, $update_query);
    
    header("Location: dashboard.php");
} else {
    header("Location: login.php?error=1");
}
exit();
?>
