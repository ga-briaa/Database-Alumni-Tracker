<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Employment_ID = $_POST['emp-id']; // Primary key for employment table
    
    // Alumni Table Data
    $Alum_ID_new = $_POST['alum-id'];
    $Alum_ID_old = $_POST['alum-old-id'];
    $Alum_FirstName = $_POST['alum-firstName'];
    $Alum_LastName = $_POST['alum-lastName'];
    
    // Employment Table Data
    $Position_ID = $_POST['position-id'];
    $Company_ID = $_POST['company-id'];
    $Location_ID = $_POST['location-id'];
    $Start_Date = $_POST['start-date'];
    $End_Date = !empty($_POST['end-date']) ? $_POST['end-date'] : NULL;

    if (!preg_match('/^\d{4}-\d{5}$/', $Alum_ID_new)) {
        die("Error: Invalid ID format.");
    }   

    $conn->begin_transaction();
    $update_success = true;

    // 1. Update Alumni Information (ID and Name)
    $sql_alumni = "UPDATE alumni 
                   SET Alum_ID = ?, 
                       Alum_FirstName = ?, 
                       Alum_LastName = ? 
                   WHERE Alum_ID = ?";
    $stmt_alumni = $conn->prepare($sql_alumni);
    $stmt_alumni->bind_param("ssss", $Alum_ID_new, $Alum_FirstName, $Alum_LastName, $Alum_ID_old);
    
    if (!$stmt_alumni->execute()) {
        $update_success = false;
        $error_alumni = $stmt_alumni->error;
    }
    $stmt_alumni->close();

    // 2. Update Employment Record
    if ($update_success) {
        $sql_employment = "UPDATE employment 
                           SET Position_ID = ?, 
                               Company_ID = ?, 
                               Location_ID = ?, 
                               Start_Date = ?, 
                               End_Date = ? 
                           WHERE Employment_ID = ?";
        $stmt_employment = $conn->prepare($sql_employment);
        $stmt_employment->bind_param("sssssi", $Position_ID, $Company_ID, $Location_ID, $Start_Date, $End_Date, $Employment_ID);
        
        if (!$stmt_employment->execute()) {
            $update_success = false;
            $error_employment = $stmt_employment->error;
        }
        $stmt_employment->close();
    }

    // 3. Commit or Rollback
    if ($update_success) {
        $conn->commit();
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&update=success");
    } else {
        $conn->rollback();
        echo "Error updating record. Alumni update error: " . ($error_alumni ?? 'None') . 
             ". Employment update error: " . ($error_employment ?? 'None');
    }

    $conn->close();
    
} else {
    echo "Invalid request.";
}
?>