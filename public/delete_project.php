<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
require_once 'dB_Connection.php';

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: Home_Page.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting Project.";
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>