<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Company_ID = $_POST['company-id'];

    $sql = "DELETE FROM company WHERE Company_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $Company_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=company&status=deleted");
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