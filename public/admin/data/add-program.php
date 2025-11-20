<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Program_ID = $_POST['program-id'];
    $Program_Name = $_POST['program-name'];
    $College_ID = $_POST['program-college'];

    $sql = "INSERT INTO program (Program_ID, Program_Name, College_ID) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $Program_ID, $Program_Name, $College_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&add=success");
    } else {
        // Error
        echo "Error adding record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>