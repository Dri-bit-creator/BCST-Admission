<?php
require_once __DIR__ . '/../includes/conn.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed");
}

$student_id = $_POST['student_id'] ?? '';

$sql = "SELECT student_id FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "exists";
} else {
    echo "available";
}


$stmt->close();
$conn->close();
