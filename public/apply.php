<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

require_once __DIR__ . '/../includes/conn.php';

$username = $_SESSION['username'];
$userEmail = $_SESSION['email'] ?? '';

if (empty($userEmail)) {
    if (isset($_SESSION['id'])) {
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $_SESSION['id']);
    } else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
    }

    $stmt->execute();
    $stmt->bind_result($foundEmail);
    if ($stmt->fetch()) {
        $userEmail = $foundEmail;
        $_SESSION['email'] = $foundEmail;
    }
    $stmt->close();
}

$application = null;

if (!empty($userEmail)) {
    $stmt = $conn->prepare("SELECT student_id, approved, enrollment_date FROM students WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
}

if (!$application && !empty($_SESSION['application_id'])) {
    $stmt = $conn->prepare("SELECT student_id, approved, enrollment_date FROM students WHERE student_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $_SESSION['application_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
}

$hasApplied = !empty($application);
$applicationId = $hasApplied ? $application['student_id'] : '';
$statusText = 'Not submitted';
$statusClass = 'status-neutral';

if ($hasApplied) {
    if ((int) $application['approved'] === 1) {
        $statusText = 'Accepted';
        $statusClass = 'status-accepted';
    } elseif ((int) $application['approved'] === 2 || (int) $application['approved'] === -1) {
        $statusText = 'Denied';
        $statusClass = 'status-denied';
    } else {
        $statusText = 'Pending admin confirmation';
        $statusClass = 'status-pending';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online Application for Admission</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <style>
    :root {
      --brand: #2e6f40;
      --brand-dark: #214f30;
      --brand-soft: #eaf3ed;
      --line: #dfe7e2;
      --text: #17211b;
      --muted: #66746b;
      --surface: #ffffff;
      --bg: #f5f8f6;
      --warning-bg: #fff7d6;
      --warning-text: #8a5a00;
      --success-bg: #dff4e7;
      --success-text: #1f6b36;
      --danger-bg: #fde5e5;
      --danger-text: #9f1d1d;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: Arial, sans-serif;
      color: var(--text);
      background:
        linear-gradient(180deg, rgba(46,111,64,0.08), rgba(46,111,64,0) 320px),
        var(--bg);
    }

    .topbar {
      background: var(--surface);
      border-bottom: 1px solid var(--line);
      position: sticky;
      top: 0;
      z-index: 20;
    }

    .topbar-inner {
      max-width: 1120px;
      margin: 0 auto;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
      font-weight: 800;
      color: var(--brand-dark);
    }

    .brand img {
      width: 42px;
      height: 42px;
      object-fit: contain;
      border-radius: 50%;
      background: #fff;
      border: 1px solid var(--line);
    }

    .brand span {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .logout-button {
      border: 1px solid #cbd8cf;
      background: #ffffff;
      color: var(--brand-dark);
      border-radius: 6px;
      padding: 10px 14px;
      font-weight: 700;
      cursor: pointer;
    }

    .page {
      max-width: 1120px;
      margin: 0 auto;
      padding: 34px 20px 42px;
    }

    .hero {
      display: grid;
      grid-template-columns: minmax(0, 1.12fr) minmax(320px, 0.88fr);
      gap: 22px;
      align-items: stretch;
    }

    .hero-main,
    .track-card,
    .info-card,
    .notice-card {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 8px;
      box-shadow: 0 14px 34px rgba(32, 55, 39, 0.08);
    }

    .hero-main {
      padding: 34px;
      position: relative;
      overflow: hidden;
    }

    .hero-main::after {
      content: "";
      position: absolute;
      right: -70px;
      top: -80px;
      width: 240px;
      height: 240px;
      border-radius: 50%;
      background: rgba(46, 111, 64, 0.08);
    }

    .eyebrow {
      margin: 0 0 12px;
      color: var(--brand);
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0;
      text-transform: uppercase;
    }

    .hero h1 {
      margin: 0;
      max-width: 620px;
      font-size: clamp(32px, 5vw, 52px);
      line-height: 1.03;
      color: var(--brand-dark);
    }

    .hero-copy {
      margin: 18px 0 0;
      max-width: 620px;
      color: var(--muted);
      font-size: 16px;
      line-height: 1.6;
    }

    .hero-actions {
      margin-top: 28px;
    }

    .primary-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 46px;
      padding: 0 20px;
      border: 0;
      border-radius: 6px;
      background: var(--brand);
      color: #ffffff;
      font-weight: 800;
      text-decoration: none;
      cursor: pointer;
    }

    .primary-button:hover {
      background: var(--brand-dark);
    }

    .notice-card {
      margin-top: 28px;
      padding: 18px;
      background: var(--brand-soft);
      border-color: #cfe2d4;
    }

    .notice-card h2 {
      margin: 0 0 8px;
      color: var(--brand-dark);
      font-size: 18px;
    }

    .notice-card p {
      margin: 0;
      color: #3f5948;
      line-height: 1.5;
    }

    .track-card {
      padding: 24px;
    }

    .track-card h2,
    .info-card h2 {
      margin: 0 0 10px;
      color: var(--brand-dark);
      font-size: 22px;
    }

    .track-card p {
      margin: 0 0 18px;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.5;
    }

    .track-form {
      display: grid;
      gap: 12px;
    }

    .track-label {
      color: #4b5d51;
      font-size: 12px;
      font-weight: 800;
    }

    .input-id {
      width: 100%;
      min-height: 44px;
      border: 1px solid #cbd8cf;
      border-radius: 6px;
      padding: 0 12px;
      font-size: 15px;
      background: #ffffff;
      outline: none;
    }

    .input-id:read-only {
      background: #f6f8f7;
      color: #33473a;
    }

    .btn-track {
      min-height: 44px;
      border: 0;
      border-radius: 6px;
      background: var(--brand);
      color: #ffffff;
      font-weight: 800;
      cursor: pointer;
    }

    .btn-track:hover {
      background: var(--brand-dark);
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      margin-top: 18px;
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 800;
    }

    .status-neutral {
      background: #eef2f0;
      color: #526158;
    }

    .status-pending {
      background: var(--warning-bg);
      color: var(--warning-text);
    }

    .status-accepted {
      background: var(--success-bg);
      color: var(--success-text);
    }

    .status-denied {
      background: var(--danger-bg);
      color: var(--danger-text);
    }

    .details-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 18px;
      margin-top: 22px;
    }

    .info-card {
      padding: 22px;
    }

    .info-card p,
    .info-card li {
      color: var(--muted);
      line-height: 1.5;
      font-size: 14px;
    }

    .info-card ul {
      margin: 12px 0 0;
      padding-left: 20px;
    }

    .footer {
      max-width: 1120px;
      margin: 0 auto;
      padding: 0 20px 28px;
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    @media (max-width: 860px) {
      .hero,
      .details-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 640px) {
      .topbar-inner {
        align-items: flex-start;
        flex-direction: column;
      }

      .brand span {
        white-space: normal;
      }

      .logout-button {
        width: 100%;
      }

      .hero-main,
      .track-card,
      .info-card {
        padding: 22px;
      }
    }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <div class="brand">
        <img src="../img/logo.png" alt="BCST Logo">
        <span>Bohol College of Science and Technology</span>
      </div>
      <form action="logout.php" method="POST" style="margin:0;">
        <button type="submit" class="logout-button">Logout</button>
      </form>
    </div>
  </header>

  <main class="page">
    <section class="hero">
      <div class="hero-main">
        <p class="eyebrow">Online Admission</p>
        <h1>Welcome, <?= htmlspecialchars($username) ?>.</h1>
        <p class="hero-copy">
          Submit and monitor your BCST application in one place. After applying, your application ID will be saved here automatically for tracking.
        </p>

        <div class="hero-actions">
          <?php if (!$hasApplied): ?>
            <a href="form.php" class="primary-button">Apply Now</a>
          <?php else: ?>
            <div class="notice-card">
              <h2>You have applied.</h2>
              <p>Please wait for the admin confirmation. You can see your application status using the track button.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <aside class="track-card">
        <h2>Track Application</h2>
        <p>Your ID No. is filled in automatically after you submit an application.</p>
        <div class="track-form">
          <label for="applicationId" class="track-label">ID No.</label>
          <input
            type="text"
            id="applicationId"
            class="input-id"
            value="<?= htmlspecialchars($applicationId) ?>"
            placeholder="<?= $hasApplied ? '' : 'Available after applying' ?>"
            readonly
          >
          <button type="button" onclick="trackApplication()" class="btn-track">Track</button>
        </div>
        <div id="statusResult" class="status-pill <?= htmlspecialchars($statusClass) ?>">Status: <?= htmlspecialchars($statusText) ?></div>
      </aside>
    </section>

    <section class="details-grid">
      <article class="info-card">
        <h2>Requirements</h2>
        <p>Prepare clear digital copies before starting the form.</p>
        <ul>
          <li>Report Card</li>
          <li>Birth Certificate</li>
          <li>Good Moral Certificate</li>
          <li>NSO/PSA</li>
          <li>Formal Picture</li>
          <li>Diploma</li>
          <li>Form 137</li>
        </ul>
      </article>

      <article class="info-card">
        <h2>Application Verification</h2>
        <p>
          Applications are reviewed by the admissions office. Use the track button to check whether your application is accepted, denied, or still pending confirmation.
        </p>
      </article>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; <span id="current-year"></span> BOHOL COLLEGE OF SCIENCE AND TECHNOLOGY INC. All rights reserved.</p>
  </footer>

  <script>
    document.getElementById('current-year').textContent = new Date().getFullYear();

    function setStatus(text, className) {
      const statusResult = document.getElementById('statusResult');
      statusResult.className = 'status-pill ' + className;
      statusResult.textContent = 'Status: ' + text;
    }

    function trackApplication() {
      const id = document.getElementById('applicationId').value.trim();

      if (!id) {
        setStatus('No application submitted yet', 'status-neutral');
        return;
      }

      fetch('check_status.php?id=' + encodeURIComponent(id))
        .then(response => response.json())
        .then(data => {
          const status = data.status || 'Not found';
          let className = 'status-neutral';

          if (status === 'Accepted') {
            className = 'status-accepted';
          } else if (status === 'Denied') {
            className = 'status-denied';
          } else if (status.indexOf('Pending') !== -1) {
            className = 'status-pending';
          }

          setStatus(status, className);
        })
        .catch(() => {
          setStatus('Unable to check right now', 'status-denied');
        });
    }
  </script>
</body>
</html>
