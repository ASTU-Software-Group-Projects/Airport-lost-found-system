<?php
// =============================================
// DATABASE CONFIGURATION
// =============================================

$db_server = getenv('DB_SERVER') ?: "localhost";
$db_username = getenv('DB_USERNAME') ?: "root";
$db_password = getenv('DB_PASSWORD') ?: "";
$db_name = getenv('DB_NAME') ?: "lost_found_db";
$db_port = getenv('DB_PORT') ?: 3306;

$conn = mysqli_connect($db_server, $db_username, $db_password, $db_name, $db_port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'Ethiopian Airlines Lost & Found');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/airport-lost-and-found-main/');
define('UPLOAD_PATH', getenv('UPLOAD_PATH') ?: $_SERVER['DOCUMENT_ROOT'] . '/airport-lost-and-found-main/uploads/');
define('MAX_FILE_SIZE', 5242880);

function generateClaimCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return 'LOST-' . $code;
}

function generateFoundCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return 'FND-' . $code;
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim(htmlspecialchars($data)));
}

function isStaffLoggedIn() {
    return isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true;
}

function uploadFile($file, $folder) {
    $target_dir = UPLOAD_PATH . $folder . '/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_extension, $allowed_types)) {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'uploads/' . $folder . '/' . $new_filename;
        }
    }
    return false;
}

function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge badge-warning"><i class="fas fa-search"></i> Pending</span>',
        'matched' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Matched</span>',
        'returned' => '<span class="badge badge-info"><i class="fas fa-box"></i> Returned</span>',
        'unclaimed' => '<span class="badge badge-warning"><i class="fas fa-clock"></i> Unclaimed</span>'
    ];
    return isset($badges[$status]) ? $badges[$status] : $badges['pending'];
}
?>
