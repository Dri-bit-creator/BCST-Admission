<?php
require_once __DIR__ . '/../conn.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Optional: confirm existence first
    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $conn->query("DELETE FROM students WHERE id = $id");
    }
}

header("Location: dashboard.php");
exit;
?>
