<?php
session_start();
require_once __DIR__ . '/../includes/conn.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $log_date = date('Y-m-d');
    $time_out = date('H:i:s');

    $stmt = $conn->prepare("
        UPDATE user_log
        SET time_out = ?, status = 'Logged Out'
        WHERE username = ? AND log_date = ?
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->bind_param("sss", $time_out, $username, $log_date);
    $stmt->execute();
    $stmt->close();
}

session_destroy();
session_unset();
session_destroy();
header("Location: index.php");
exit;
