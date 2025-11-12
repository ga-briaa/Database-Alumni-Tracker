<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Alum_ID = $_POST['alum-id'];

    $sql = "DELETE FROM alumni WHERE Alum_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $Alum_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-info&status=deleted");
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