<?php
$servername = "localhost";
$username = "root"; // Default for XAMPP/WAMP
$password = "prince"; // Leave blank if no password
$database = "weather_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
