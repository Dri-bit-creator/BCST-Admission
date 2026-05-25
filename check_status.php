<?php
// check_status.php

header('Content-Type: application/json');

$conn = null;
require_once __DIR__ . '/conn.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo json_encode(["error" => "No application ID provided"]);
    exit;
}

$appId = $conn->real_escape_string(trim($_GET['id']));

// Assuming 'student_id' is the application ID in your table
$sql = "SELECT approved FROM students WHERE student_id = '$appId' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Determine status: approved=1 means Approved, 0 means Pending
    // Adjust here if you add a 'denied' or 'status' field in your table
    $status = $row['approved'] == 1 ? "Approved" : "Pending";

    echo json_encode(["status" => $status]);
} else {
    echo json_encode(["status" => "Not found"]);
}

$conn->close();
