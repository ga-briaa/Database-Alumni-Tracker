<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Alum_ID = $_POST['alum-id'];
    $Alum_FirstName = $_POST['alum-firstName'];
    $Alum_LastName = $_POST['alum-lastName'];
    $Alum_ContactInfo = $_POST['alum-contactInfo'];
    $Status_ID = $_POST['alum-status'];

    if (!preg_match('/^\d{4}-\d{5}$/', $Alum_ID)) {
        die("Error: Invalid ID format.");
    }   

    $sql = "INSERT INTO alumni (Alum_ID, Alum_FirstName, Alum_LastName, Alum_ContactInfo, Status_ID) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $Alum_ID, $Alum_FirstName, $Alum_LastName, $Alum_ContactInfo, $Status_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-info&add=success");
    } else {
        // Error
        echo "Error adding record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>