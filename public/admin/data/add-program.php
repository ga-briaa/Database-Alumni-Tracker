<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Program_ID = $_POST['program-id'];
    $Program_Name = $_POST['program-name'];
    $College_ID = $_POST['program-college'];

    // Check if Program_ID already exists
    $checkSql = "SELECT COUNT(*) FROM program WHERE Program_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $Program_ID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate ID found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO program (Program_ID, Program_Name, College_ID)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $Program_ID, $Program_Name, $College_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&add=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&add=error");
        exit();
        // echo "Error adding record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>