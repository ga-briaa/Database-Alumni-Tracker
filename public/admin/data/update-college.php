<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $College_ID_new = $_POST['college-id'];
    $College_ID_old = $_POST['college-old-id'];
    $College_Name = $_POST['college-name'];

    // Check if new College_ID already exists and is different from the old ID
    if ($College_ID_new !== $College_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM college WHERE College_ID = ? AND College_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $College_ID_new, $College_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE college
            SET College_ID = ?,
                College_Name = ?
            WHERE College_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $College_ID_new, $College_Name, $College_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&update=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&update=error");
        exit();
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>