<?php
$servername = "localhost";
$username = "root";
$password = ""; // leave empty if using XAMPP
$dbname = "bcst_db"; // make sure this matches your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
