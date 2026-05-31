<?php
// MySQL connection bootstrap for local development (XAMPP).
require_once __DIR__ . '/db_config.php';

// Use defined constants where available, otherwise fall back to typical XAMPP defaults
$servername = defined('DB_HOST') ? DB_HOST : 'localhost';
$username = defined('DB_USER') ? DB_USER : 'root';
$password = defined('DB_PASS') ? DB_PASS : '';
$dbname   = defined('DB_NAME') ? DB_NAME : 'bcst_db';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

