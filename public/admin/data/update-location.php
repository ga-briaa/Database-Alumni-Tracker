<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Location_ID = $_POST['location-old-id']; // This is the ID of the record to update
    $Country = $_POST['location-country'];
    $Region = $_POST['location-region'];
    $City = $_POST['location-city'];

    $sql = "UPDATE location
            SET Country = ?,
                Region = ?,
                City = ?
            WHERE Location_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $Country, $Region, $City, $Location_ID);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=location&update=success");
        exit();
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=location&update=error");
        exit();
        // echo "Error updating record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>