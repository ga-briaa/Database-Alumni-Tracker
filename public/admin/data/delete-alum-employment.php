<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Employment_ID = $_POST['emp-id'];

    $sql = "DELETE FROM employment WHERE Employment_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $Employment_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&status=deleted");
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