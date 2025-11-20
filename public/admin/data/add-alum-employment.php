<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Alum_ID = $_POST['alum-id'];
    $Position_ID = $_POST['position-id'];
    $Company_ID = $_POST['company-id'];
    $Location_ID = $_POST['location-id'];
    $Start_Date = $_POST['start-date'];
    $End_Date = !empty($_POST['end-date']) ? $_POST['end-date'] : NULL;

    $sql = "INSERT INTO employment (Alum_ID, Position_ID, Company_ID, Location_ID, Start_Date, End_Date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // 's' for strings, 's' for dates (passed as strings in PHP)
    $stmt->bind_param("ssssss", $Alum_ID, $Position_ID, $Company_ID, $Location_ID, $Start_Date, $End_Date);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&add=success");
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