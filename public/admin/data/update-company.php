<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Company_ID_new = $_POST['company-id'];
    $Company_ID_old = $_POST['company-old-id'];
    $Company_Name = $_POST['company-name'];

    // Check if new Company_ID already exists and is different from the old ID
    if ($Company_ID_new !== $Company_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM company WHERE Company_ID = ? AND Company_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("is", $Company_ID_new, $Company_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE company
            SET Company_ID = ?,
                Company_Name = ?
            WHERE Company_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $Company_ID_new, $Company_Name, $Company_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&update=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&update=error");
        exit();
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>