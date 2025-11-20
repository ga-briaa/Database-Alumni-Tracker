<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $College_ID = $_POST['college-id'];
    $College_Name = $_POST['college-name'];

    $sql = "INSERT INTO college (College_ID, College_Name) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $College_ID, $College_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&add=success");
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