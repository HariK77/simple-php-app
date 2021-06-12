<?php
$servername = "localhost";
$username = "maxx";
$password = "maxx@123";
$database = "simple_php_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>