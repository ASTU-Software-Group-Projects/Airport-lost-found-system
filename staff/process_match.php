<?php
require_once '../config.php';
if (!isStaffLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['lost_id']) || !isset($_POST['found_id'])) {
    header("Location: match_items.php");
    exit();
}

$lost_id = (int)$_POST['lost_id'];
$found_id = (int)$_POST['found_id'];

mysqli_begin_transaction($conn);

try {
    // Check if items are still available
    $lost_check = mysqli_query($conn, "SELECT status FROM lost_items WHERE id = $lost_id FOR UPDATE");
    $found_check = mysqli_query($conn, "SELECT status FROM found_items WHERE id = $found_id FOR UPDATE");
    
    $lost_row = mysqli_fetch_assoc($lost_check);
    $found_row = mysqli_fetch_assoc($found_check);
    
    if ($lost_row['status'] !== 'pending' || $found_row['status'] !== 'unclaimed') {
        throw new Exception("One or both items are no longer available for matching.");
    }

    // Update found item
    $update_found = "UPDATE found_items SET matched_to = $lost_id, status = 'matched' WHERE id = $found_id";
    if (!mysqli_query($conn, $update_found)) throw new Exception("Error updating found item.");

    // Update lost item
    $update_lost = "UPDATE lost_items SET status = 'matched' WHERE id = $lost_id";
    if (!mysqli_query($conn, $update_lost)) throw new Exception("Error updating lost item.");

    mysqli_commit($conn);
    $_SESSION['match_success'] = "<i class=\"fas fa-check-circle\"></i> Items matched successfully! The status has been updated to 'matched'.";
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['match_success'] = "<i class=\"fas fa-times-circle\"></i> Error: " . $e->getMessage();
}

header("Location: match_items.php");
exit();
?>
