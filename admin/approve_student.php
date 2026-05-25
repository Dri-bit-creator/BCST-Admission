<?php
require_once __DIR__ . '/../conn.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update the 'approved' status to 1 (approved)
    $stmt = $conn->prepare("UPDATE students SET approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Success: Redirect back to the dashboard
        header("Location: dashboard.php?message=approved");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
