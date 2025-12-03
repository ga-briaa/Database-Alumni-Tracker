<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Location_ID_new = $_POST['location-id'];
    $Location_ID_old = $_POST['location-old-id'];
    $Country = $_POST['country'];
    $Region = $_POST['region'];
    $City = $_POST['city'];

    // Check if new Location_ID already exists and is different from the old ID
    if ($Location_ID_new !== $Location_ID_old) {
        $checkSql = "SELECT COUNT(*) FROM location WHERE Location_ID = ? AND Location_ID != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("is", $Location_ID_new, $Location_ID_old);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_row();
        $checkStmt->close();

        if ($row[0] > 0) {
            // Duplicate ID found
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=location&error=duplicate");
            exit();
        }
    }

    $sql = "UPDATE location
            SET Location_ID = ?,
                Country = ?,
                Region = ?,
                City = ?
            WHERE Location_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $Location_ID_new, $Country, $Region, $City, $Location_ID_old);
    
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