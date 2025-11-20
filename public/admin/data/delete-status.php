<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Status_ID = $_POST['status-id'];

    $sql = "DELETE FROM status WHERE Status_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $Status_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=status&status=deleted");
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