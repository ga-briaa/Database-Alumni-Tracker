<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Company_ID = $_POST['company-id'];
    $Company_Name = $_POST['company-name'];

    $sql = "INSERT INTO company (Company_ID, Company_Name) 
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $Company_ID, $Company_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&add=success");
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