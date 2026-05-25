<?php
// Save all previous POST values in hidden inputs
$hidden_fields = '';
foreach ($_POST as $key => $value) {
    $hidden_fields .= "<input type='hidden' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "' />\n";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enrollment - Upload Documents</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <link rel="stylesheet" href="/bcst/assets/css/apply.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-4xl mx-auto px-4 py-10">

    <!-- Back Button -->
    <button onclick="history.back()" class="mb-6 flex items-center text-green-600 hover:text-green-800 transition-all">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      Back To Enrollment Step 1
    </button>

    <div class="bg-white shadow-md rounded-lg p-8">
      <h2 class="text-3xl font-bold text-green-700 mb-6">Enrollment Form - Step 2: Upload Documents</h2>
      
      <form action="submit_student.php" method="POST" enctype="multipart/form-data">
        <?= $hidden_fields ?>

        <!-- Email Field -->
        <div class="mb-6">
          <label class="block font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" name="email" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label class="block font-medium text-gray-700 mb-1">Report Card</label>
            <input type="file" name="card_id" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Birth Certificate</label>
            <input type="file" name="birth_cert" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Good Moral Certificate</label>
            <input type="file" name="good_moral" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">NSO/PSA</label>
            <input type="file" name="nso_psa" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Formal Picture</label>
            <input type="file" name="formal_picture" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block font-medium text-gray-700 mb-1">Diploma</label>
            <input type="file" name="diploma" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div class="sm:col-span-2">
            <label class="block font-medium text-gray-700 mb-1">Form 137</label>
            <input type="file" name="form_137" required class="block w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>
        </div>

        <div class="mt-8">
          <button type="submit" class="w-full md:w-auto bg-green-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-green-700 transition-all">
            Submit Enrollment
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
