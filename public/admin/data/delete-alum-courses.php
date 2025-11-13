<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Grad_ID = $_POST['grad-id'];

    $sql = "DELETE FROM graduation WHERE Grad_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $Grad_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&status=deleted");
    } else {
        // Error
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>