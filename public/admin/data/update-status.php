<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Status_ID_new = $_POST['status-id'];
    $Status_ID_old = $_POST['status-old-id'];
    $Status_Name = $_POST['status-name'];

    $sql = "UPDATE status 
            SET Status_ID = ?, 
                Status_Name = ? 
            WHERE Status_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $Status_ID_new, $Status_Name, $Status_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&update=success");
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