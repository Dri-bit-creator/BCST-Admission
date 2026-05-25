<?php
session_start();
require_once __DIR__ . '/../conn.php';

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
            // ✅ Log the login event correctly
            $log_date = date('Y-m-d');
            $time_in = date('H:i:s');

            $stmtLog = $conn->prepare(
                "INSERT INTO user_log (username, log_date, time_in, status) VALUES (?, ?, ?, ?)"
            );

            if ($role === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $username;
                $status = 'Admin Logged In';
                $stmtLog->bind_param("ssss", $username, $log_date, $time_in, $status);
                $stmtLog->execute();
                $stmtLog->close();
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
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
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{ --brand: #2e6f40; }
    html,body{ height:100%; margin:0; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; }
    body {
      min-height:100vh;
      background-color:#f7fbf9;
      background-image: radial-gradient(circle at center, rgba(46,111,64,0.06), rgba(46,111,64,0.02)), url('../img/logo.png');
      background-repeat:no-repeat;
      background-position:center center;
      background-size:60vmin;
      background-attachment:fixed;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:2rem;
    }

    .auth-card{
      width:100%;
      max-width:420px;
      background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(255,255,255,0.90));
      border-radius:16px;
      padding:28px;
      box-shadow: 0 10px 30px rgba(16,24,40,0.08);
      border:1px solid rgba(46,111,64,0.06);
      backdrop-filter: blur(6px);
    }

    .brand{ display:flex; align-items:center; gap:12px; justify-content:center; margin-bottom:14px; }
    .brand img{ width:64px; height:64px; border-radius:12px; object-fit:cover; box-shadow:0 6px 18px rgba(16,24,40,0.08); }
    .title{ text-align:center; margin-bottom:6px; color:var(--brand); font-weight:700; font-size:20px; }
    .subtitle{ text-align:center; color:#6b7280; font-size:13px; margin-bottom:18px; }

    .form-input{ width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; outline:none; transition:box-shadow .15s, border-color .15s; }
    .form-input:focus{ border-color:var(--brand); box-shadow:0 0 0 4px rgba(46,111,64,0.06); }

    .btn-primary{ width:100%; background:var(--brand); color:#fff; padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:600; box-shadow:0 8px 20px rgba(46,111,64,0.12); transition: transform .12s ease, background .12s ease; }
    .btn-primary:hover{ transform: translateY(-1px); background:#1f6b36; }

    .helper{ text-align:center; color:#6b7280; font-size:13px; margin-top:14px; }

    @media (max-width:420px){ .auth-card{ padding:18px; border-radius:12px; } .brand img{ width:56px; height:56px; } }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="brand">
      <img src="../img/logo.png" alt="BCST Logo">
    </div>

    <div class="title">BCST Admission Application</div>
    <div class="subtitle">Please log in to continue</div>

    <form method="POST" action="" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" required class="form-input">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" required class="form-input">
      </div>

      <div>
        <button type="submit" name="login" class="btn-primary">Login</button>
      </div>
    </form>

    <div class="helper">No account? <a href="register.php" class="text-green-600 font-medium hover:underline">Register here</a></div>
  </div>
</body>
</html>
