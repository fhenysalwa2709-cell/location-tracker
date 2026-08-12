<?php
/**
 * Logout Process
 * /process/logout.php
 */

require_once '../config/auth.php';

// Log activity before logout
require_once '../config/database.php';
$user_id = getCurrentUserId();
if ($user_id) {
    $stmt = $conn->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent)
        VALUES (?, 'logout', 'User logged out', ?, ?)
    ");
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt->bind_param("iss", $user_id, $ip, $user_agent);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Destroy session
logout();

?>
