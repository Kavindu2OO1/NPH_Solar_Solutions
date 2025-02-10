<?php
session_start();
require_once 'dB_Connection.php';

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: Home_Page.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?delete=success");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?delete=error");
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
?>