<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Alum_ID_new = $_POST['alum-id'];
    $Alum_ID_old = $_POST['alum-old-id'];
    $Alum_FirstName = $_POST['alum-firstName'];
    $Alum_LastName = $_POST['alum-lastName'];
    $Alum_ContactInfo = $_POST['alum-contactInfo'];
    $Status_ID = $_POST['alum-status'];

    if (!preg_match('/^\d{4}-\d{5}$/', $Alum_ID_new)) {
        die("Error: Invalid ID format.");
    }   

    // Check if new Alum_ID already exists and is different from the old ID
    if ($Alum_ID_new !== $Alum_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM alumni WHERE Alum_ID = ? AND Alum_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $Alum_ID_new, $Alum_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-info&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE alumni
            SET Alum_ID = ?,
                Alum_FirstName = ?,
                Alum_LastName = ?,
                Alum_ContactInfo = ?,
                Status_ID = ?
            WHERE Alum_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $Alum_ID_new, $Alum_FirstName, $Alum_LastName, $Alum_ContactInfo, $Status_ID, $Alum_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-info&update=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-info&update=error");
        exit();
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>