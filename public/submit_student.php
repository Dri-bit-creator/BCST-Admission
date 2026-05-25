<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once __DIR__ . '/../includes/conn.php';
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . ($conn->connect_error ?? 'unknown'));
}

function studentIdExists($conn, $studentId) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

function generateUniqueStudentId($conn) {
    do {
        $studentId = (string) random_int(100000, 999999);
    } while (studentIdExists($conn, $studentId));

    return $studentId;
}

// Collect form data
$enrollment_date = $_POST['enrollment_date'] ?? '';
$student_id = $_POST['student_id'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? '';
$pob = $_POST['pob'] ?? '';
$fathers_name = $_POST['fathers_name'] ?? '';
$contact_no = $_POST['contact_no'] ?? '';
$mothers_name = $_POST['mothers_name'] ?? '';
$religion = $_POST['religion'] ?? '';
$school_level = $_POST['year_level'] ?? '';
$email = $_SESSION['email'] ?? ($_POST['email'] ?? '');

if (!preg_match('/^\d{6}$/', $student_id)) {
    $student_id = generateUniqueStudentId($conn);
}

// File upload handling: store in project-level uploads/ directory
$upload_dir_fs = __DIR__ . '/../uploads/';
if (!file_exists($upload_dir_fs)) {
    mkdir($upload_dir_fs, 0777, true);
}

function uploadFile($fileKey, $prefix) {
    global $upload_dir_fs;
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $filename = $prefix . '_' . time() . '_' . basename($_FILES[$fileKey]['name']);
        $destination = $upload_dir_fs . $filename;
        move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destination);
        // return a web-accessible relative path
        return 'uploads/' . $filename;
    }
    return '';
}

$report_card_path     = uploadFile('card_id', 'card_id');
$birth_cert_path      = uploadFile('birth_cert', 'birth_cert');
$good_moral_path      = uploadFile('good_moral', 'good_moral');
$nso_psa_path         = uploadFile('nso_psa', 'nso_psa');
$formal_picture_path  = uploadFile('formal_picture', 'formal_picture');
$diploma_path         = uploadFile('diploma', 'diploma');
$form_137_path        = uploadFile('form_137', 'form_137');

if (studentIdExists($conn, $student_id)) {
    $student_id = generateUniqueStudentId($conn);
}

// SQL insert
$sql = "INSERT INTO students 
(enrollment_date, student_id, full_name, gender, date_of_birth, place_of_birth, fathers_name, contact_no, mothers_name, religion, school_level, email, report_card_file, birth_cert_file, good_moral_file, nso_psa_file, formal_picture_file, diploma_file, form_137_file)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssssssssss",
    $enrollment_date,
    $student_id,
    $full_name,
    $gender,
    $dob,
    $pob,
    $fathers_name,
    $contact_no,
    $mothers_name,
    $religion,
    $school_level,
    $email,
    $report_card_path,
    $birth_cert_path,
    $good_moral_path,
    $nso_psa_path,
    $formal_picture_path,
    $diploma_path,
    $form_137_path
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollment Confirmation</title>
    <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="shortcut icon" href="../img/logo.png">
    <link rel="stylesheet" href="/bcst/assets/css/apply.css">
    <style>
        body { font-family: Arial, sans-serif; background:rgb(251, 251, 251); text-align: center; padding: 100px; }
        .message-box { background: #ffffff; border: 2px solid #008000; padding: 30px; border-radius: 10px; display: inline-block; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .message-box h1 { color: #008000; }
        .message-box p { font-size: 16px; margin: 10px 0; }
        .redirect-note { margin-top: 20px; }
        .back-button { margin-top: 20px; padding: 10px 20px; background-color: rgb(7, 65, 238); color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; text-decoration: none; }
        .back-button:hover { background-color: #008000; }
    </style>
</head>
<body>
    <div class="message-box">
        <?php
        if ($stmt->execute()) {
            $_SESSION['application_id'] = $student_id;
            echo "<h1>Thank you for your enrollment!</h1>";
            echo "<p>Your Application ID is: <strong>" . htmlspecialchars($student_id) . "</strong></p>";
        } else {
            echo "<h1>Something went wrong:</h1>";
            echo "<p>" . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
        <div class="redirect-note">
            <a href="apply.php" class="back-button">Back to Application Page</a>
        </div>
    </div>
</body>
</html>
