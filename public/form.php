<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Enrollment - Step 1</title>
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
      Back To Online Admission
    </button>
    

    <!-- Enrollment Card -->
    <div class="bg-white shadow-md rounded-lg p-8">
      <h2 class="text-3xl font-bold text-green-700 mb-6">Enrollment Form - Step 1</h2>
      
      <form action="upload_section.php" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          
          <!-- Form Fields -->
          <div>
            <label class="block mb-1 font-medium text-gray-700">Date</label>
            <input type="date" name="enrollment_date" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">ID No.</label>
            <input type="text" name="student_id" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Full Name</label>
            <input type="text" name="full_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Gender</label>
            <select name="gender" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Date of Birth</label>
            <input type="date" name="dob" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Place of Birth</label>
            <input type="text" name="pob" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Father's Name</label>
            <input type="text" name="fathers_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Mother's Name</label>
            <input type="text" name="mothers_name" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Contact No.</label>
            <input type="text" name="contact_no" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">Religion</label>
            <input type="text" name="religion" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
          </div>

          <div>
            <label class="block mb-1 font-medium text-gray-700">School Level</label>
            <select name="year_level" required class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
              <option value="">Select</option>
              <option value="HighSchool">High School</option>
              <option value="College">College</option>
            </select>
          </div>
        </div>

        <!-- Submit -->
        <div class="mt-8">
          <button type="submit" class="w-full md:w-auto bg-green-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-green-700 transition-all">
            Next: Upload Documents
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
