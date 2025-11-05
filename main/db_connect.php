<!--Connect to the database-->
<?php
    // Initialize value of Database
    $serverName = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "group_database";

    $conn = new mysqli($serverName, $username, $password, $databaseName);

    // Check connection to database
    if ($conn->connect_error) {
        die("Connection Error." . $conn->connect_error);
    }

    // Close connection to database
    $conn->close();
?>