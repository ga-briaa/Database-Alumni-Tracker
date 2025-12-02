<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Degree_ID_new = $_POST['degree-id'];
    $Degree_ID_old = $_POST['degree-old-id'];
    $Degree_Abbreviation = $_POST['degree-abbreviation'];
    $Degree_Name = $_POST['degree-name'];

    // Check if new Degree_ID already exists and is different from the old ID
    if ($Degree_ID_new !== $Degree_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM degree WHERE Degree_ID = ? AND Degree_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $Degree_ID_new, $Degree_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE degree
            SET Degree_ID = ?,
                Degree_Abbreviation = ?,
                Degree_Name = ?
            WHERE Degree_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $Degree_ID_new, $Degree_Abbreviation, $Degree_Name, $Degree_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&update=success");
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&update=error");
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>