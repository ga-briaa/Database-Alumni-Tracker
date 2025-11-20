<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Alum_ID = $_POST['alum-id'];
    $Degree_ID = $_POST['degree-id'];
    $Program_ID = $_POST['program-id'];
    $Grad_Year = $_POST['grad-year'];

    $sql_graduation = "INSERT INTO graduation (Alum_ID, Degree_ID, Program_ID, Grad_Year) 
                       VALUES (?, ?, ?, ?)";
    $stmt_graduation = $conn->prepare($sql_graduation);
    
    $stmt_graduation->bind_param("ssss", $Alum_ID, $Degree_ID, $Program_ID, $Grad_Year);
    
    if ($stmt_graduation->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&add=success");
    } else {
        // Error
        echo "Error adding record: " . $stmt_graduation->error;
    }
    
    $stmt_graduation->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>