<?php
session_start();
require_once __DIR__ . '/../conn.php';

$error = "";

// Handle form submission via users table (role = 'admin')
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login | BCST</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #2e6f40;
    }
  </style>
</head>

<body class="bg-green-700 min-h-screen flex items-center justify-center relative px-4">


  <!-- Login Card -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
    
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <img src="../img/logo.png" alt="BCST Logo" class="w-20 h-20 object-contain rounded-full border border-gray-200 shadow-sm">
    </div>

    <h2 class="text-2xl font-bold text-center text-green-700 mb-2">Admin Portal</h2>
    <p class="text-center text-gray-500 mb-6">Authorized personnel only</p>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-4 text-sm text-center">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input type="text" id="username" name="username" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <button type="submit"
              class="w-full bg-green-600 text-white py-2 rounded-md font-semibold hover:bg-green-700 transition-all">
        Login
      </button>
    </form>
  </div>
</body>
</html>
