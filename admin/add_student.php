<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/../includes/conn.php';

    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $contact_no = $_POST['contact_no'];
    $religion = $_POST['religion'];
    $enrollment_date = $_POST['enrollment_date'];

    $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, gender, date_of_birth, place_of_birth, fathers_name, mothers_name, contact_no, religion, enrollment_date, approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssssssss", $student_id, $full_name, $gender, $date_of_birth, $place_of_birth, $fathers_name, $mothers_name, $contact_no, $religion, $enrollment_date);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../img/logo.png" type="image/png">
    <link rel="shortcut icon" href="../img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="background-color: #2e6f40;">
  <div class="container mx-auto px-4 py-8">
  </div>
</body>

<body class="bg-gray-100 p-10">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Student</h2>
        <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Student ID</label>
                <input type="text" name="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Place of Birth</label>
                <input type="text" name="place_of_birth" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                <input type="text" name="fathers_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mother's Name</label>
                <input type="text" name="mothers_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_no" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Religion</label>
                <input type="text" name="religion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Enrollment Date</label>
                <input type="date" name="enrollment_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="md:col-span-2 text-right">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Submit</button>

            <!-- Back to Dashboard Button -->
            <div class="mb-6">
             <a href="dashboard.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard</a>
         </div>
        </form>
    </div>
</body>
</html>
