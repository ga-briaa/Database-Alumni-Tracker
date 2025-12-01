<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Company_ID_new = $_POST['company-id'];
    $Company_ID_old = $_POST['company-old-id'];
    $Company_Name = $_POST['company-name'];

    $sql = "UPDATE company 
            SET Company_ID = ?, 
                Company_Name = ? 
            WHERE Company_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $Company_ID_new, $Company_Name, $Company_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&update=success");
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