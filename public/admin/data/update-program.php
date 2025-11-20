<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Program_ID_new = $_POST['program-id'];
    $Program_ID_old = $_POST['program-old-id'];
    $Program_Name = $_POST['program-name'];
    $College_ID = $_POST['program-college'];

    $sql = "UPDATE program 
            SET Program_ID = ?, 
                Program_Name = ?, 
                College_ID = ? 
            WHERE Program_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $Program_ID_new, $Program_Name, $College_ID, $Program_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&update=success");
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