<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Location_ID = $_POST['location-id'];

    $sql = "DELETE FROM location WHERE Location_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $Location_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=location&status=deleted");
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