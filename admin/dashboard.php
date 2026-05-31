<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

// Optional: session timeout after 30 minutes
$timeout_duration = 1800; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: admin.php?session_expired=1");
    exit();
}
$_SESSION['last_activity'] = time();

require_once __DIR__ . '/../includes/conn.php';

function requirementDocumentLink($studentId, $fileColumn) {
    return 'view_document.php?id=' . urlencode((string) $studentId) . '&file=' . urlencode($fileColumn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Enrollment Dashboard</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/bcst/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../img/logo.png" type="image/png">
  <link rel="shortcut icon" href="../img/logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .status-pending {
      background-color: #fef9c3;
      color: #b45309;
    }
    .status-approved {
      background-color: #d1fae5;
      color: #2e6f40;
    }
    .status-denied {
      background-color: #fee2e2;
      color: #991b1b;
    }
    .action-btn {
      transition: all 0.2s ease;
    }
    .action-btn:hover {
      transform: translateY(-1px);
    }
    .student-row:hover {
      background-color: #f8fafc;
    }
    @media (max-width: 1024px) {
      .responsive-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }
    }
  </style>
</head>

<body style="background-color: #2e6f40;">
  <div class="container mx-auto px-4 py-8">
    <?php
    $search = $_GET['search'] ?? '';
    $searchQuery = $conn->real_escape_string($search);

    $total = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
    $pending = $conn->query("SELECT COUNT(*) as pending FROM students WHERE approved = 0")->fetch_assoc()['pending'];
    $approved = $conn->query("SELECT COUNT(*) as approved FROM students WHERE approved = 1")->fetch_assoc()['approved'];
    $denied = $conn->query("SELECT COUNT(*) as denied FROM students WHERE approved IN (2, -1)")->fetch_assoc()['denied'];
    $approved_today = $conn->query("SELECT COUNT(*) as approved_today FROM students WHERE approved = 1 AND CAST(enrollment_date AS date) = CURRENT_DATE")->fetch_assoc()['approved_today'];

    if (!empty($search)) {
        $result = $conn->query("SELECT * FROM students WHERE student_id LIKE '%$searchQuery%' ORDER BY id DESC");
    } else {
        $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
    }
    ?>

    <div class="w-full max-w-screen-2xl mx-auto px-4 py-8">
      <!-- Heading and buttons -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div class="mb-4 md:mb-0">
          <h1 class="text-4xl font-extrabold text-white">Student Enrollment Dashboard</h1>
          <p class="text-lg text-white mt-2">Manage all student enrollments in one place</p>
        </div>
        <div class="flex space-x-3">
          <a href="add_student.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Student
          </a>
          <a href="../public/logout.php" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg flex items-center">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </div>

      <!-- Search Bar -->
      <form method="GET" class="my-6">
        <div class="flex items-center gap-2">
          <input 
            type="text" 
            name="search" 
            value="<?php echo htmlspecialchars($search); ?>" 
            placeholder="Search Application ID" 
            class="w-full md:w-96 px-4 py-2 border border-blue-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium">
            <i class="fas fa-search mr-2"></i> Search
          </button>
        </div>
      </form>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-gray-500">Total Students</p>
              <h3 class="text-2xl font-bold mt-1"><?php echo $total; ?></h3>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
              <i class="fas fa-users text-green-700 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-gray-500">Pending Approvals</p>
              <h3 class="text-2xl font-bold mt-1"><?php echo $pending; ?></h3>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
              <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-gray-500">Approved Today</p>
              <h3 class="text-2xl font-bold mt-1"><?php echo $approved_today; ?></h3>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
              <i class="fas fa-check-circle text-green-700 text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-gray-500">Denied Applications</p>
              <h3 class="text-2xl font-bold mt-1"><?php echo $denied; ?></h3>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
              <i class="fas fa-times-circle text-red-700 text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="min-w-[1600px] table-auto divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of birth</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Father name</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mother name</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">religion</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School-level</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report-Card</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birth Cert</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Good Moral</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NSO-PSA</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formal-pic</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diploma</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form-137</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
      <?php while ($row = $result->fetch_assoc()): ?>    
      <tr class="student-row">
      <td class="px-6 py-4 text-sm text-gray-900"><?php echo $row['id']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['enrollment_date']; ?></td>
      <td class="px-6 py-4 text-sm text-[#2e6f40] font-semibold"><?php echo $row['student_id']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-900"><?php echo $row['full_name']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['gender']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['date_of_birth']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['fathers_name']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['mothers_name']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['contact_no']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['religion']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-500"><?php echo $row['school_level']; ?></td>
      <td class="px-6 py-4 text-sm text-gray-900"><?php echo $row['email']; ?></td>


      <!-- Report Card -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['report_card_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'report_card_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Birth Certificate -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['birth_cert_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'birth_cert_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Good Moral -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['good_moral_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'good_moral_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- NSO/PSA -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['nso_psa_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'nso_psa_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Formal Picture -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['formal_picture_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'formal_picture_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Diploma -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['diploma_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'diploma_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Form 137 -->
      <td class="px-6 py-4 text-sm">
        <?php if (!empty($row['form_137_file'])): ?>
          <a href="<?php echo htmlspecialchars(requirementDocumentLink($row['id'], 'form_137_file')); ?>" target="_blank" class="text-[#2e6f40] hover:underline">View</a>
        <?php else: ?>
          <span class="text-gray-400 italic">N/A</span>
        <?php endif; ?>
      </td>

      <!-- Status -->
      <td class="px-6 py-4">
        <?php
          $statusClass = 'status-pending';
          $statusText = 'Pending';
          if ((int) $row['approved'] === 1) {
              $statusClass = 'status-approved';
              $statusText = 'Accepted';
          } elseif ((int) $row['approved'] === 2 || (int) $row['approved'] === -1) {
              $statusClass = 'status-denied';
              $statusText = 'Denied';
          }
        ?>
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
          <?php echo $statusText; ?>
        </span>
      </td>

      <!-- Actions -->
      <td class="px-6 py-4">
        <div class="flex space-x-2">
          <!-- Edit -->
          <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="action-btn bg-green-100 text-green-700 p-2 rounded-lg hover:bg-green-200" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <!-- Delete -->
          <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="action-btn bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200" onclick="return confirm('Are you sure you want to delete this student?');" title="Delete">
            <i class="fas fa-trash"></i>
          </a>
          <!-- Approve -->
          <?php if ((int) $row['approved'] === 0): ?>
            <a href="approve_student.php?id=<?php echo $row['id']; ?>" class="action-btn bg-blue-100 text-blue-700 p-2 rounded-lg hover:bg-blue-200" title="Approve">
              <i class="fas fa-check"></i>
            </a>
            <a href="deny_student.php?id=<?php echo $row['id']; ?>" class="action-btn bg-orange-100 text-orange-700 p-2 rounded-lg hover:bg-orange-200" onclick="return confirm('Deny this application?');" title="Deny">
              <i class="fas fa-times"></i>
            </a>
          <?php endif; ?>
              </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>

  <!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Graph Section -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
  <h2 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
    <i class="fas fa-chart-bar text-green-700 mr-2"></i> Enrollment Statistics
  </h2>
  
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Bar Chart -->
    <div class="w-full h-64">
      <canvas id="barChart"></canvas>
    </div>

    <!-- Pie Chart -->
    <div class="w-full h-64">
      <canvas id="pieChart"></canvas>
    </div>
  </div>
</div>

<script>
  // Chart data from PHP
  const total = <?php echo $total; ?>;
  const pending = <?php echo $pending; ?>;
  const approved = <?php echo $approved; ?>;
  const denied = <?php echo $denied; ?>;

  // Bar Chart
  const ctx1 = document.getElementById('barChart').getContext('2d');
  new Chart(ctx1, {
    type: 'bar',
    data: {
      labels: ['Total Applicants', 'Accepted', 'Pending', 'Denied'],
      datasets: [{
        label: 'Number of Students',
        data: [total, approved, pending, denied],
        backgroundColor: ['#2e6f40', '#16a34a', '#facc15', '#dc2626'],
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        title: { display: true, text: 'Student Enrollment Overview', font: { size: 18 } }
      },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } }
      }
    }
  });

  // Pie Chart
  const ctx2 = document.getElementById('pieChart').getContext('2d');
  new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: ['Accepted', 'Pending', 'Denied'],
      datasets: [{
        data: [approved, pending, denied],
        backgroundColor: ['#16a34a', '#facc15', '#dc2626'],
        borderColor: ['#ffffff'],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        title: { display: true, text: 'Approval Distribution', font: { size: 16 } }
      }
    }
  });
</script>

</body>
</html>
