<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Program_ID_new = $_POST['program-id'];
    $Program_ID_old = $_POST['program-old-id'];
    $Program_Name = $_POST['program-name'];
    $College_ID = $_POST['program-college'];

    // Check if new Program_ID already exists and is different from the old ID
    if ($Program_ID_new !== $Program_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM program WHERE Program_ID = ? AND Program_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $Program_ID_new, $Program_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE program
            SET Program_ID = ?,
                Program_Name = ?,
                College_ID = ?
            WHERE Program_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $Program_ID_new, $Program_Name, $College_ID, $Program_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&update=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&update=error");
        exit();
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>