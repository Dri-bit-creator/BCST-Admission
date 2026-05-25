<?php
// ✅ START SESSION AND REQUIRE LOGIN
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
  <link rel="stylesheet" href="/bcst/assets/css/apply.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      color: #333;
      background: #fff;
      line-height: 1.6;
    }

    /* Header */
    .header {
      background-color: #2e6f40;
      color: #fff;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      font-size: 16px;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo {
      width: 40px;
      height: 40px;
    }

    .input-id {
      padding: 8px 12px;
      font-size: 14px;
      border-radius: 6px;
      border: none;
      outline: none;
    }

    .btn-track {
      padding: 8px 14px;
      margin-left: 8px;
      border: none;
      background: #fff;
      color: #2e6f40;
      font-size: 14px;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Main */
    .main {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
      text-align: center;
    }

    .main-title {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .button-group {
      margin-bottom: 20px;
    }

    .btn-primary,
    .btn-outline {
      padding: 12px 22px;
      font-size: 15px;
      margin: 6px;
      border-radius: 6px;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #2e6f40;
      color: white;
      border: none;
    }

    .btn-outline {
      border: 2px solid #2e6f40;
      color: #2e6f40;
      background: transparent;
    }

    .info-text {
      font-size: 15px;
      margin-bottom: 30px;
    }

    details {
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 15px;
      padding: 15px;
    }

    summary {
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    details div {
      margin-top: 10px;
      font-size: 14px;
    }

    ul {
      margin: 10px 0;
      padding-left: 25px;
      font-size: 14px;
    }

    .footer {
      background-color: #2e6f40;
      color: #fff;
      padding: 30px 15px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .footer h3 {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .footer p {
      margin: 5px 0;
      font-size: 14px;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.3);
      padding-top: 15px;
      text-align: center;
      font-size: 13px;
      color: #dcdcdc;
    }

    @media (max-width: 768px) {
      .main-title {
        font-size: 22px;
      }

      .btn-primary,
      .btn-outline {
        width: 100%;
      }

      .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .header-right {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
      }

      .input-id {
        flex: 1;
        min-width: 150px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <img src="../img/logo.png" class="logo" alt="Logo" />
      <span>BOHOL COLLEGE OF SCIENCE AND TECHNOLOGY INC</span>
    </div>
    <div class="header-right">
        <span>Track Application</span>
      <input type="text" placeholder="Enter Application ID" class="input-id" id="applicationId" />
      <button onclick="trackApplication()" class="btn-track">Track</button>
    </div>
  </header>

  <main class="main">
    <h1 class="main-title">Online Application for Admission</h1>
    <div class="button-group">
      <a href="form.php" class="btn-primary">APPLY NOW</a>
    </div>

    <p class="info-text">
      Welcome <strong><?php echo $_SESSION['username']; ?></strong>!<br>
      The portal for the online application for BOHOL COLLEGE OF SCIENCE AND
      TECHNOLOGY INC – School for Senior High School applicants and for incoming
      college students applying to the
      <strong>Hospitality Management (HM)</strong> program only, for the academic
      year 2026–2027.
    </p>

    <p id="statusResult"></p>

    <section class="details">
      <details>
        <summary>Requirements</summary>
        <div>
          <p><strong>GENERAL REQUIREMENTS:</strong></p>
          <ul>
            <li>Fully accomplished Online Admission Form</li>
            <li>2x2 colored ID photo</li>
            <li>Birth Certificate (PSA)</li>
          </ul>
          <p><strong>ADDITIONAL REQUIREMENTS:</strong></p>
          <ul>
            <li>Grade 12 Card</li>
            <li>Certificate of Good Moral</li>
            <li>Certificate of Diploma</li>
          </ul>
        </div>
      </details>

      <details>
        <summary>Qualifications</summary>
        <div>Senior High School graduate or equivalent</div>
      </details>

      <details>
        <summary>Application Procedure</summary>
        <div>Fill out the form, upload documents, and submit online.</div>
      </details>

      <details>
        <summary>Application Verification</summary>
        <div>Applications are verified by the admissions office before approval.</div>
      </details>
    </section>
  </main>
  <footer class="footer">
    <div class="footer-grid">
      <div>
        <h3>Email</h3>
        <p>saihilbero30@gmail.com</p>
        <p>admissiom.bcst.edu.ph</p>
      </div>
      <div>
        <h3>Facebook</h3>
        <p>BCST Main Campus</p>
        <p>BCST Sagbayan</p>
      </div>
      <div>
        <h3>Mobile Number</h3>
        <p>0981 468 1554</p>
        <p>09123456789</p>
      </div>
      <div>
        <h3>Account</h3>
        <form action="logout.php" method="POST" style="margin:0;">
          <button type="submit" class="btn-track" style="background:#b82e2e; color:white;">
            Logout
          </button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© <span id="current-year"></span> BOHOL COLLEGE OF SCIENCE AND TECHNOLOGY INC. All rights reserved.</p>
    </div>
  </footer>
    <script>
        // Set current year in footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
  <script>
  function trackApplication(){
    const id = document.getElementById('applicationId').value.trim();
    if(!id) return alert('Enter application ID');
    fetch('check_status.php?id='+encodeURIComponent(id))
      .then(r=>r.json()).then(data=>{
        document.getElementById('statusResult').textContent = 'Status: ' + (data.status || 'Not found');
      }).catch(()=> alert('Error checking status'));
  }
  </script>
</body>
</html>
