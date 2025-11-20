<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $College_ID = $_POST['college-id'];

    $sql = "DELETE FROM college WHERE College_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $College_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=college&status=deleted");
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