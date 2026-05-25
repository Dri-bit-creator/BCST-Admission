<?php
header('Content-Type: application/json');

$conn = null;
require_once __DIR__ . '/../includes/conn.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo json_encode(["error" => "No application ID provided"]);
    exit;
}

$appId = $conn->real_escape_string(trim($_GET['id']));

$sql = "SELECT approved FROM students WHERE student_id = '$appId' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ((int) $row['approved'] === 1) {
        $status = "Accepted";
    } elseif ((int) $row['approved'] === 2 || (int) $row['approved'] === -1) {
        $status = "Denied";
    } else {
        $status = "Pending admin confirmation";
    }

    echo json_encode(["status" => $status]);
} else {
    echo json_encode(["status" => "Not found"]);
}

$conn->close();
