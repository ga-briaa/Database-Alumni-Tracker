<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Degree_ID_new = $_POST['degree-id'];
    $Degree_ID_old = $_POST['degree-old-id'];
    $Degree_Abbreviation = $_POST['degree-abbreviation'];
    $Degree_Name = $_POST['degree-name'];

    $sql = "UPDATE degree 
            SET Degree_ID = ?, 
                Degree_Abbreviation = ?,
                Degree_Name = ? 
            WHERE Degree_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $Degree_ID_new, $Degree_Abbreviation, $Degree_Name, $Degree_ID_old);
    
    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&update=success");
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