<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $College_ID = $_POST['college-id'];
    $College_Name = $_POST['college-name'];

    // Check if College_ID already exists
    $checkSql = "SELECT COUNT(*) FROM college WHERE College_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $College_ID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate ID found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO college (College_ID, College_Name)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $College_ID, $College_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&add=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&add=error");
        exit();
        // echo "Error adding record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>