<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Alum_ID = trim($_POST['alum-id']);
    $Position_ID = trim($_POST['position-id']);
    $Company_ID = trim($_POST['company-id']);
    $Location_ID = trim($_POST['location-id']);
    $Start_Date = trim($_POST['start-date']);
    $End_Date = !empty($_POST['end-date']) ? trim($_POST['end-date']) : NULL;

    // Check if the combination of Alum_ID, Position_ID, Company_ID, Location_ID, and Start_Date already exists
    $checkSql = "SELECT COUNT(*) FROM employment WHERE Alum_ID = ? AND Position_ID = ? AND Company_ID = ? AND Location_ID = ? AND Start_Date = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("sssss", $Alum_ID, $Position_ID, $Company_ID, $Location_ID, $Start_Date);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate entry found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO employment (Alum_ID, Position_ID, Company_ID, Location_ID, Start_Date, End_Date)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // 's' for strings, 's' for dates (passed as strings in PHP)
    $stmt->bind_param("ssssss", $Alum_ID, $Position_ID, $Company_ID, $Location_ID, $Start_Date, $End_Date);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&add=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&add=error");
        exit();
        // echo "Error adding record: " . $stmt->error; // For debugging
    }
    
    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>