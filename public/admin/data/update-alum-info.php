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
    } else {
        // Error
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>