<?php
// SITE SETTINGS
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/airport-lost-and-found-main1/');
define('SITE_NAME', 'Ethiopian Airlines - Lost & Found');

// DATABASE SETTINGS
$db_server   = getenv('DB_SERVER') ?: 'localhost';
$db_username = getenv('DB_USERNAME') ?: 'root';
$db_password = getenv('DB_PASSWORD') ?: '';
$db_name     = getenv('DB_NAME') ?: 'lost_found_db';
$db_port     = getenv('DB_PORT') ?: '3306';

// CONNECTION
$conn = mysqli_init();

// If we are on Vercel (Cloud), enable SSL
if (getenv('DB_SERVER')) {
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
    $link = mysqli_real_connect($conn, $db_server, $db_username, $db_password, $db_name, $db_port, NULL, MYSQLI_CLIENT_SSL);
} else {
    // Local XAMPP connection
    $link = mysqli_real_connect($conn, $db_server, $db_username, $db_password, $db_name, $db_port);
}

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Global functions or additional configs can go here
?>
