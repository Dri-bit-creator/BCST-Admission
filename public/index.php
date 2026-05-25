<?php
session_start();
require_once __DIR__ . '/../includes/conn.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Log the login event
            $log_date = date('Y-m-d');
            $time_in = date('H:i:s');

            $stmtLog = $conn->prepare(
                "INSERT INTO user_log (username, log_date, time_in, status) VALUES (?, ?, ?, ?)"
            );

            if ($role === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $status = 'Admin Logged In';
                $stmtLog->bind_param("ssss", $username, $log_date, $time_in, $status);
                $stmtLog->execute();
                $stmtLog->close();
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $status = 'Logged In';
                $stmtLog->bind_param("ssss", $username, $log_date, $time_in, $status);
                $stmtLog->execute();
                $stmtLog->close();
                header("Location: apply.php");
                exit;
            }
        } else {
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        echo "<script>alert('Email not found');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BCST Admission Application - Login</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root { --brand: #2e6f40; --brand-dark: #214f30; --brand-soft: #eaf3ed; --text: #111827; --muted: #6b7280; }
    * { box-sizing: border-box; }
    html, body { min-height: 100%; margin: 0; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    body { min-height: 100vh; background: #f4f8f5; color: var(--text); display: grid; place-items: center; padding: 32px; }
    .login-shell { width: min(100%, 980px); min-height: 620px; display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(360px, 0.88fr); overflow: hidden; background: #ffffff; border: 1px solid #dfe7e2; border-radius: 8px; box-shadow: 0 24px 60px rgba(23, 42, 29, 0.12); }
  </style>
</head>
<body>
  <main class="login-shell">
    <section class="brand-panel" aria-label="BCST identity">
      <div class="school-mark">
        <img src="../img/logo.png" alt="BCST Logo">
        <h1>Bohol College of Science and Technology</h1>
        <p>Online admission portal for student applications and enrollment tracking.</p>
      </div>
      <div class="brand-footer">&copy; <span id="current-year"></span> BCST. All rights reserved.</div>
    </section>

    <section class="form-panel" aria-label="User login form">
      <div class="auth-card">
        <p class="portal-name">BCST Admission</p>

        <h2 class="title">Welcome Back!</h2>
        <p class="subtitle">Don't have an account? <a href="register.php">Create a new account now</a>.</p>

        <form method="POST" action="">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required class="form-input" autocomplete="email">
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required class="form-input" autocomplete="current-password">
          </div>

          <button type="submit" name="login" class="btn-primary">Login Now</button>
        </form>

        <div class="helper">Use your registered email and password to continue.</div>
      </div>
    </section>
  </main>
  <script>document.getElementById('current-year').textContent = new Date().getFullYear();</script>
</body>
</html>
