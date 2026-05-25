<?php
$servername = "localhost";
$username = "root";      // default for XAMPP
$password = "";          // default for XAMPP (no password)
$dbname = "bcst_db";     // make sure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
