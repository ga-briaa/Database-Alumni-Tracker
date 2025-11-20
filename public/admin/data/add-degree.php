<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Degree_ID = $_POST['degree-id'];
    $Degree_Name = $_POST['degree-name'];

    $sql = "INSERT INTO degree (Degree_ID, Degree_Name) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $Degree_ID, $Degree_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&add=success");
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