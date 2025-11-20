<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Program_ID = $_POST['program-id'];

    $sql = "DELETE FROM program WHERE Program_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $Program_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=program&status=deleted");
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