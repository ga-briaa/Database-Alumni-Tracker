<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Alum_ID = $_POST['alum-id'];
    $Degree_ID = $_POST['degree-id'];
    $Program_ID = $_POST['program-id'];
    $Grad_Year = $_POST['grad-year'];

    // Check if the combination of Alum_ID, Degree_ID, Program_ID, and Grad_Year already exists
    $checkSql = "SELECT COUNT(*) FROM graduation WHERE Alum_ID = ? AND Degree_ID = ? AND Program_ID = ? AND Grad_Year = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ssss", $Alum_ID, $Degree_ID, $Program_ID, $Grad_Year);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate entry found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&error=duplicate");
        exit();
    }

    $sql_graduation = "INSERT INTO graduation (Alum_ID, Degree_ID, Program_ID, Grad_Year)
                       VALUES (?, ?, ?, ?)";
    $stmt_graduation = $conn->prepare($sql_graduation);
    
    $stmt_graduation->bind_param("ssss", $Alum_ID, $Degree_ID, $Program_ID, $Grad_Year);
    
    if ($stmt_graduation->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&add=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&add=error");
        exit();
        // echo "Error adding record: " . $stmt_graduation->error; // For debugging
    }
    
    $stmt_graduation->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>