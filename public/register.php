<?php
require_once __DIR__ . '/../includes/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $checkStmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ? LIMIT 1");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $existing = $checkStmt->get_result()->fetch_assoc();
        $checkStmt->close();

        if ($existing) {
            if ($existing['username'] === $username) {
                $error = "That username is already taken. Please choose another one.";
            } else {
                $error = "That email is already registered. Please log in instead.";
            }
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);

            try {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->bind_param("sss", $username, $email, $hashed);
                $stmt->execute();
                $stmt->close();

                echo "<script>alert('Registered successfully! You can now log in.'); window.location='index.php';</script>";
                exit;
            } catch (mysqli_sql_exception $e) {
                if ((int) $e->getCode() === 1062) {
                    $error = "Username or email already exists. Please use different details.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
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
    :root {
      --brand: #2e6f40;
      --brand-dark: #214f30;
      --brand-soft: #eaf3ed;
      --line: #dfe7e2;
      --text: #17211b;
      --muted: #66746b;
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      min-height: 100%;
      margin: 0;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      color: var(--text);
    }

    body {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 32px;
      background:
        linear-gradient(135deg, rgba(46, 111, 64, 0.10), rgba(46, 111, 64, 0) 42%),
        #f5f8f6;
    }

    .register-shell {
      width: min(100%, 1020px);
      min-height: 650px;
      display: grid;
      grid-template-columns: minmax(360px, 0.95fr) minmax(0, 1.05fr);
      overflow: hidden;
      background: #ffffff;
      border: 1px solid var(--line);
      border-radius: 8px;
      box-shadow: 0 24px 60px rgba(23, 42, 29, 0.12);
    }

    .form-panel {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 46px;
      background: #ffffff;
    }

    .register-card {
      width: 100%;
      max-width: 390px;
    }

    .portal-name {
      margin: 0 0 34px;
      color: var(--brand-dark);
      font-size: 22px;
      font-weight: 800;
    }

    .register-card h1 {
      margin: 0 0 8px;
      font-size: 30px;
      line-height: 1.1;
      font-weight: 800;
    }

    .subtitle {
      margin: 0 0 26px;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.5;
    }

    .alert {
      margin-bottom: 18px;
      border: 1px solid #f4b8b8;
      background: #fff1f1;
      color: #9f1d1d;
      border-radius: 6px;
      padding: 12px 14px;
      font-size: 14px;
      line-height: 1.45;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      margin-bottom: 7px;
      color: #526158;
      font-size: 12px;
      font-weight: 800;
    }

    .form-input {
      width: 100%;
      min-height: 44px;
      border: 0;
      border-bottom: 2px solid #d1d5db;
      border-radius: 0;
      padding: 9px 0;
      background: transparent;
      color: var(--text);
      font-size: 14px;
      outline: none;
      transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .form-input:focus {
      border-color: var(--brand);
      box-shadow: 0 2px 0 rgba(46, 111, 64, 0.12);
    }

    .register-button {
      width: 100%;
      min-height: 48px;
      margin-top: 6px;
      border: 0;
      border-radius: 6px;
      background: var(--brand);
      color: #ffffff;
      cursor: pointer;
      font-weight: 800;
      transition: transform 0.12s ease, background 0.12s ease;
    }

    .register-button:hover {
      transform: translateY(-1px);
      background: var(--brand-dark);
    }

    .helper {
      margin: 22px 0 0;
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    .helper a {
      color: var(--brand);
      font-weight: 800;
      text-decoration: none;
    }

    .helper a:hover {
      text-decoration: underline;
    }

    .identity-panel {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 52px;
      color: #ffffff;
      background:
        linear-gradient(135deg, rgba(255,255,255,0.09) 0 1px, transparent 1px 100%),
        linear-gradient(145deg, #2e6f40 0%, #255f38 48%, #163d24 100%);
      background-size: 72px 72px, auto;
      isolation: isolate;
    }

    .identity-panel::before {
      content: "";
      position: absolute;
      inset: 42px 28px auto auto;
      width: 230px;
      height: 230px;
      border: 1px solid rgba(255,255,255,0.14);
      transform: rotate(18deg);
      z-index: -1;
    }

    .logo-block {
      display: grid;
      gap: 18px;
      align-content: start;
      max-width: 430px;
    }

    .logo-block img {
      width: 118px;
      height: 118px;
      object-fit: contain;
      border-radius: 50%;
      background: #ffffff;
      padding: 10px;
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.22);
    }

    .logo-block h2 {
      margin: 0;
      font-size: clamp(32px, 4vw, 48px);
      line-height: 1.05;
      font-weight: 800;
    }

    .logo-block p {
      margin: 0;
      max-width: 370px;
      color: rgba(255, 255, 255, 0.82);
      line-height: 1.55;
      font-size: 15px;
    }

    .steps {
      display: grid;
      gap: 12px;
      margin-top: 40px;
    }

    .step {
      display: flex;
      align-items: center;
      gap: 12px;
      color: rgba(255, 255, 255, 0.88);
      font-size: 14px;
      font-weight: 700;
    }

    .step-number {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.28);
    }

    .identity-footer {
      color: rgba(255, 255, 255, 0.62);
      font-size: 13px;
    }

    @media (max-width: 860px) {
      body {
        padding: 0;
        background: #ffffff;
      }

      .register-shell {
        min-height: 100vh;
        grid-template-columns: 1fr;
        border: 0;
        border-radius: 0;
      }

      .form-panel {
        order: 2;
        align-items: flex-start;
        padding: 38px 28px;
      }

      .identity-panel {
        order: 1;
        min-height: 330px;
        padding: 38px 28px;
      }

      .logo-block img {
        width: 88px;
        height: 88px;
      }

      .steps {
        margin-top: 28px;
      }
    }
  </style>
</head>
<body>
  <main class="register-shell">
    <section class="form-panel" aria-label="Registration form">
      <div class="register-card">
        <p class="portal-name">BCST Admission</p>
        <h1>Create Account</h1>
        <p class="subtitle">Register once, then continue to your online admission application.</p>

        <?php if (!empty($error)): ?>
          <div class="alert">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required class="form-input" autocomplete="username">
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required class="form-input" autocomplete="email">
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required class="form-input" autocomplete="new-password">
          </div>

          <div class="form-group">
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" required class="form-input" autocomplete="new-password">
          </div>

          <button type="submit" class="register-button">Create Account</button>
        </form>

        <p class="helper">
          Already registered? <a href="index.php">Login here</a>
        </p>
      </div>
    </section>

    <section class="identity-panel" aria-label="BCST identity">
      <div>
        <div class="logo-block">
          <img src="../img/logo.png" alt="BCST Logo">
          <h2>Bohol College of Science and Technology</h2>
          <p>Create your account to start your application, upload requirements, and track admin confirmation.</p>
        </div>

        <div class="steps">
          <div class="step"><span class="step-number">1</span><span>Create your account</span></div>
          <div class="step"><span class="step-number">2</span><span>Submit your application</span></div>
          <div class="step"><span class="step-number">3</span><span>Track your status</span></div>
        </div>
      </div>

      <div class="identity-footer">&copy; <span id="current-year"></span> BCST. All rights reserved.</div>
    </section>
  </main>
  <script>
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
</body>
</html>
