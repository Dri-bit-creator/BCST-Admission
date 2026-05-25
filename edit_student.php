<?php
require_once __DIR__ . '/conn.php';

// Check for student ID in URL
if (!isset($_GET['id'])) {
    echo "No student ID provided.";
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_date = $_POST['enrollment_date'];
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $pob = $_POST['pob'];
    $fathers_name = $_POST['fathers_name'];
    $contact_no = $_POST['contact_no'];
    $mothers_name = $_POST['mothers_name'];
    $religion = $_POST['religion'];
    $year_level = $_POST['year_level'];
    $email = $_POST['email'];

    // ✅ Check if Student ID is empty
    if (empty($student_id)) {
        echo "<script>alert('Student ID is required.'); window.history.back();</script>";
        exit;
    }

    // ✅ Check for duplicate Student ID (excluding this ID)
    $check_sql = "SELECT id FROM students WHERE student_id = ? AND id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $student_id, $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('Student ID already exists. Please use a different one.'); window.history.back();</script>";
        exit;
    }

    // ✅ Proceed with update
    $sql = "UPDATE students SET 
        enrollment_date=?, student_id=?, full_name=?, gender=?, date_of_birth=?, 
        place_of_birth=?, fathers_name=?, contact_no=?, mothers_name=?, religion=?, school_level=?, email=?
        WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssi", 
        $enrollment_date, $student_id, $full_name, $gender, $dob, 
        $pob, $fathers_name, $contact_no, $mothers_name, $religion, $school_level, $email, $id
    );

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

// Fetch student data
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$student = $result->fetch_assoc();
if (!$student) {
    echo "Student not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student Record</title>
    <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="shortcut icon" href="img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #2e6f40;">
  <div class="container mx-auto px-4 py-8">
  </div>
</body>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Edit Student Record</h2>
                <p class="text-gray-600 text-sm">Update student information below</p>
            </div>
            <a href="dashboard.php" class="text-blue-600 hover:underline flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label>Enrollment Date</label>
                <input type="date" name="enrollment_date" value="<?= htmlspecialchars($student['enrollment_date']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Student ID</label>
                <input type="text" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" class="form-input w-full px-4 py-2 border rounded" required>
            </div>
            <div class="md:col-span-2">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Gender</label>
                <select name="gender" class="form-input w-full px-4 py-2 border rounded">
                    <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?= htmlspecialchars($student['date_of_birth']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Place of Birth</label>
                <input type="text" name="pob" value="<?= htmlspecialchars($student['place_of_birth']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Father's Name</label>
                <input type="text" name="fathers_name" value="<?= htmlspecialchars($student['fathers_name']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Contact Number</label>
                <input type="text" name="contact_no" value="<?= htmlspecialchars($student['contact_no']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Mother's Name</label>
                <input type="text" name="mothers_name" value="<?= htmlspecialchars($student['mothers_name']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>Religion</label>
                <input type="text" name="religion" value="<?= htmlspecialchars($student['religion']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>
            <div>
                <label>School Level</label>
                <select name="school_level" class="form-input w-full px-4 py-2 border rounded" required>
                    <option value="">Select School Level</option>
                    <option value="HighSchool" <?= $student['school_level'] == 'HighSchool' ? 'selected' : '' ?>>HighSchool</option>
                    <option value="College" <?= $student['school_level'] == 'College' ? 'selected' : '' ?>>College</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" class="form-input w-full px-4 py-2 border rounded">
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                <a href="dashboard.php" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Record
                </button>
            </div>
        </form>
    </div>
</body>
</html>
