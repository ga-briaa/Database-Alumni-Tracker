<?php
session_start(); // MUST BE INCLUDED TO SAVE LOGIN INFO !

include '../src/database-config.php';

// Clear all session variables
$_SESSION = array();

session_destroy();
// Redirect to home page after logout
header("Location: " . BASE_URL . "index.php");
exit();
?>