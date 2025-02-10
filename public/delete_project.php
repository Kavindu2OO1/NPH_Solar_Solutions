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
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: admin_panel.php?delete=success");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: admin_panel.php?delete=error");
        exit();
    }
} else {
    $conn->close();
    header("Location: admin_panel.php");
    exit();
}
?>