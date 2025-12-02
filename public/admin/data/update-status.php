<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Status_ID_new = $_POST['status-id'];
    $Status_ID_old = $_POST['status-old-id'];
    $Status_Name = $_POST['status-name'];

    // Check if new Status_ID already exists and is different from the old ID
    if ($Status_ID_new !== $Status_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM status WHERE Status_ID = ? AND Status_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $Status_ID_new, $Status_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE status
            SET Status_ID = ?,
                Status_Name = ?
            WHERE Status_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $Status_ID_new, $Status_Name, $Status_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&update=success");
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&update=error");
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>