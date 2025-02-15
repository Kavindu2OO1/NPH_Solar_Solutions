<?php
require_once '../includes/session_manager.php';

$sessionManager = new SessionManager();
$sessionManager->logout();

header("Location: Home_Page.php");
exit();
?>
