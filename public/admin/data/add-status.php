<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Status_ID = $_POST['status-id'];
    $Status_Name = $_POST['status-name'];

    // Check if Status_ID already exists
    $checkSql = "SELECT COUNT(*) FROM status WHERE Status_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $Status_ID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate ID found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO status (Status_ID, Status_Name)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $Status_ID, $Status_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&add=success");
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&add=error");
        // echo "Error adding record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>