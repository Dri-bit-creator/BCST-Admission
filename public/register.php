<?php
require_once __DIR__ . '/../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash password
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed);

        if ($stmt->execute()) {
            echo "<script>alert('Registered successfully! You can now log in.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Username or Email already exists!');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCST Admission Application - Register</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: white;
      background-image: url('../img/logo.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 700px;
      background-attachment: fixed;
      opacity: 0.98;
    }
  </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

  <div class="bg-white bg-opacity-95 border border-gray-200 shadow-xl rounded-2xl p-8 w-full max-w-md backdrop-blur-sm">
    <!-- Header -->
    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold text-green-700">BCST Admission For Application</h1>
      <p class="text-gray-500 text-sm mt-1">Create your account below</p>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="" class="space-y-5">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" required
               class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" required
               class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
               class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <div>
        <label for="confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" name="confirm" required
               class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <button type="submit"
              class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg shadow-md transition duration-200">
        Register
      </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
      Already registered? <a href="login.php" class="text-green-600 font-medium hover:underline">Login here</a>
    </p>
  </div>

</body>
</html>
