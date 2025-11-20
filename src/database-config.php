<?php
// Define base URL
define('BASE_URL', '/projects/Database-Alumni-Tracker/public/');

// Initialize value of Database for alumni data
$serverName = "localhost";
$username = "root";
$password = "";
$databaseName = "group_database";

$conn = new mysqli($serverName, $username, $password, $databaseName);

// Check connection to alumni database
if ($conn->connect_error) {
    die("Connection Error." . $conn->connect_error);
}

// Initialize value of Database for login
$serverNameLogin = "localhost";
$usernameLogin = "root";
$passwordLogin = "";
$databaseNameLogin = "database_login";

$connLogin = new mysqli($serverNameLogin, $usernameLogin, $passwordLogin, $databaseNameLogin);

// Check connection to login database
if ($connLogin->connect_error) {
    die("Connection Error." . $connLogin->connect_error);
}
?>