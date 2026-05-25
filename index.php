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
            // ✅ Log the login event correctly
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
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --brand: #2e6f40;
      --brand-dark: #214f30;
      --brand-soft: #eaf3ed;
      --text: #111827;
      --muted: #6b7280;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      min-height: 100%;
      margin: 0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    body {
      min-height: 100vh;
      background: #f4f8f5;
      color: var(--text);
      display: grid;
      place-items: center;
      padding: 32px;
    }

    .login-shell {
      width: min(100%, 980px);
      min-height: 620px;
      display: grid;
      grid-template-columns: minmax(0, 1.12fr) minmax(360px, 0.88fr);
      overflow: hidden;
      background: #ffffff;
      border: 1px solid #dfe7e2;
      border-radius: 8px;
      box-shadow: 0 24px 60px rgba(23, 42, 29, 0.12);
    }

    .brand-panel {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 56px 64px;
      color: #ffffff;
      background:
        linear-gradient(135deg, rgba(255,255,255,0.10) 0 1px, transparent 1px 100%),
        linear-gradient(150deg, #2e6f40 0%, #245a34 54%, #173f25 100%);
      background-size: 76px 76px, auto;
      isolation: isolate;
    }

    .brand-panel::after {
      content: "";
      position: absolute;
      inset: 34px;
      border: 1px solid rgba(255, 255, 255, 0.13);
      transform: skewY(-11deg);
      transform-origin: center;
      z-index: -1;
    }

    .school-mark {
      display: grid;
      gap: 24px;
      max-width: 430px;
    }

    .school-mark img {
      width: 128px;
      height: 128px;
      object-fit: contain;
      border-radius: 50%;
      background: #ffffff;
      padding: 10px;
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.20);
    }

    .school-mark h1 {
      margin: 0;
      font-size: clamp(34px, 5vw, 52px);
      line-height: 1.04;
      font-weight: 800;
    }

    .school-mark p {
      margin: 0;
      max-width: 350px;
      color: rgba(255, 255, 255, 0.82);
      font-size: 16px;
      line-height: 1.45;
    }

    .brand-footer {
      position: absolute;
      left: 64px;
      bottom: 34px;
      color: rgba(255, 255, 255, 0.62);
      font-size: 13px;
    }

    .form-panel {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px;
      background: #ffffff;
    }

    .auth-card {
      width: 100%;
      max-width: 360px;
    }

    .portal-name {
      margin: 0 0 52px;
      color: var(--brand-dark);
      font-size: 22px;
      font-weight: 800;
    }

    .title {
      margin: 0 0 8px;
      color: var(--text);
      font-size: 28px;
      line-height: 1.12;
      font-weight: 800;
    }

    .subtitle {
      margin: 0 0 28px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.45;
    }

    .subtitle a,
    .helper a {
      color: var(--brand);
      font-weight: 700;
      text-decoration: none;
    }

    .subtitle a:hover,
    .helper a:hover {
      text-decoration: underline;
    }

    .form-group {
      margin-bottom: 22px;
    }

    .form-group label {
      display: block;
      margin-bottom: 7px;
      color: #6b7280;
      font-size: 12px;
      font-weight: 700;
    }

    .form-input {
      width: 100%;
      min-height: 44px;
      padding: 9px 0;
      border: 0;
      border-bottom: 2px solid #d1d5db;
      border-radius: 0;
      background: transparent;
      color: var(--text);
      outline: none;
      font-size: 14px;
      transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .form-input:focus {
      border-color: var(--brand);
      box-shadow: 0 2px 0 rgba(46, 111, 64, 0.12);
    }

    .btn-primary {
      width: 100%;
      min-height: 48px;
      border: 0;
      border-radius: 6px;
      background: var(--brand);
      color: #ffffff;
      cursor: pointer;
      font-weight: 800;
      transition: transform 0.12s ease, background 0.12s ease;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      background: var(--brand-dark);
    }

    .helper {
      margin-top: 22px;
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    @media (max-width: 820px) {
      body {
        padding: 0;
        background: #ffffff;
      }

      .login-shell {
        min-height: 100vh;
        grid-template-columns: 1fr;
        border: 0;
        border-radius: 0;
      }

      .brand-panel {
        min-height: 300px;
        padding: 40px 28px;
      }

      .school-mark {
        gap: 18px;
      }

      .school-mark img {
        width: 96px;
        height: 96px;
      }

      .brand-footer {
        position: static;
        margin-top: 36px;
      }

      .form-panel {
        align-items: flex-start;
        padding: 40px 28px;
      }

      .portal-name {
        margin-bottom: 34px;
      }
    }
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
  <script>
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
</body>
</html>
