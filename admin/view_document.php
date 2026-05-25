<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

require_once __DIR__ . '/../includes/conn.php';

$allowedFiles = [
    'report_card_file',
    'birth_cert_file',
    'good_moral_file',
    'nso_psa_file',
    'formal_picture_file',
    'diploma_file',
    'form_137_file',
];

$documentLabels = [
    'report_card_file' => 'Report Card',
    'birth_cert_file' => 'Birth Certificate',
    'good_moral_file' => 'Good Moral',
    'nso_psa_file' => 'NSO/PSA',
    'formal_picture_file' => 'Formal Picture',
    'diploma_file' => 'Diploma',
    'form_137_file' => 'Form 137',
];

$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$fileColumn = $_GET['file'] ?? '';

if ($studentId <= 0 || !in_array($fileColumn, $allowedFiles, true)) {
    http_response_code(400);
    exit('Invalid document request.');
}

$sql = "SELECT `$fileColumn` FROM students WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student || empty($student[$fileColumn])) {
    http_response_code(404);
    exit('Document not found.');
}

$storedPath = str_replace('\\', '/', $student[$fileColumn]);
$filename = basename(parse_url($storedPath, PHP_URL_PATH) ?: $storedPath);
$uploadsDir = realpath(__DIR__ . '/../uploads');

if (!$uploadsDir) {
    http_response_code(404);
    exit('Uploads folder is missing.');
}

$filePath = realpath($uploadsDir . DIRECTORY_SEPARATOR . $filename);

if (!$filePath || strpos($filePath, $uploadsDir . DIRECTORY_SEPARATOR) !== 0 || !is_file($filePath)) {
    http_response_code(404);
    exit('Document file is missing.');
}

$mimeType = 'application/octet-stream';
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $detectedType = finfo_file($finfo, $filePath);
        if ($detectedType) {
            $mimeType = $detectedType;
        }
        finfo_close($finfo);
    }
}

if (isset($_GET['raw']) && $_GET['raw'] === '1') {
    header('Content-Type: ' . $mimeType);
    header('Content-Length: ' . filesize($filePath));
    header('Content-Disposition: inline; filename="' . str_replace(['"', "\r", "\n"], '', $filename) . '"');
    header('X-Content-Type-Options: nosniff');
    readfile($filePath);
    exit();
}

$rawUrl = 'view_document.php?' . http_build_query([
    'id' => $studentId,
    'file' => $fileColumn,
    'raw' => '1',
]);
$documentTitle = $documentLabels[$fileColumn] ?? 'Enrollment Document';
$isImage = strpos($mimeType, 'image/') === 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($documentTitle) ?> | Document Viewer</title>
  <link rel="icon" href="/bcst/favicon.ico" type="image/x-icon">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      color: #111827;
    }

    .viewer-header {
      position: sticky;
      top: 0;
      z-index: 10;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 14px 20px;
      background: #ffffff;
      border-bottom: 1px solid #e5e7eb;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .viewer-title {
      min-width: 0;
    }

    .viewer-title h1 {
      margin: 0;
      font-size: 18px;
      color: #2e6f40;
    }

    .viewer-title p {
      margin: 4px 0 0;
      color: #6b7280;
      font-size: 13px;
      word-break: break-word;
    }

    .exit-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-height: 40px;
      padding: 0 16px;
      border: 0;
      border-radius: 6px;
      background: #2e6f40;
      color: #ffffff;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      white-space: nowrap;
    }

    .exit-button:hover {
      background: #245a34;
    }

    .viewer-body {
      min-height: calc(100vh - 69px);
      padding: 20px;
    }

    .document-frame {
      display: block;
      width: 100%;
      height: calc(100vh - 110px);
      border: 1px solid #d1d5db;
      background: #ffffff;
      border-radius: 6px;
    }

    .document-image {
      display: block;
      max-width: 100%;
      max-height: calc(100vh - 110px);
      margin: 0 auto;
      border: 1px solid #d1d5db;
      background: #ffffff;
      border-radius: 6px;
      object-fit: contain;
    }

    @media (max-width: 640px) {
      .viewer-header {
        align-items: flex-start;
        flex-direction: column;
      }

      .exit-button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <header class="viewer-header">
    <div class="viewer-title">
      <h1><?= htmlspecialchars($documentTitle) ?></h1>
      <p><?= htmlspecialchars($filename) ?></p>
    </div>
    <a href="dashboard.php" class="exit-button">Exit</a>
  </header>

  <main class="viewer-body">
    <?php if ($isImage): ?>
      <img src="<?= htmlspecialchars($rawUrl) ?>" alt="<?= htmlspecialchars($documentTitle) ?>" class="document-image">
    <?php else: ?>
      <iframe src="<?= htmlspecialchars($rawUrl) ?>" title="<?= htmlspecialchars($documentTitle) ?>" class="document-frame"></iframe>
    <?php endif; ?>
  </main>
</body>
</html>
