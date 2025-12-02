<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Company_ID = $_POST['company-id'];
    $Company_Name = $_POST['company-name'];

    // Check if Company_ID already exists
    $checkSql = "SELECT COUNT(*) FROM company WHERE Company_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $Company_ID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate ID found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO company (Company_ID, Company_Name)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $Company_ID, $Company_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&add=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&add=error");
        exit();
        // echo "Error adding record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>