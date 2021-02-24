<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "facebook";

// Create database connection
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>