<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $College_ID_new = $_POST['college-id'];
    $College_ID_old = $_POST['college-old-id'];
    $College_Name = $_POST['college-name'];

    $sql = "UPDATE college 
            SET College_ID = ?, 
                College_Name = ? 
            WHERE College_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $College_ID_new, $College_Name, $College_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&update=success");
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