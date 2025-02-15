<?php
require_once '../includes/session_manager.php';
$sessionManager = new SessionManager();
require_once 'dB_Connection.php';

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: Home_Page.php");
    exit();
}

// Ensure product_id is passed
if (isset($_GET['product_id'])) {
    $id = intval($_GET['product_id']);
    
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Order item(s) deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting order item(s).";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
