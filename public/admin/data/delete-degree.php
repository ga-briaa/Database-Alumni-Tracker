<?php
require_once '../../../src/database-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Degree_ID = $_POST['degree-id'];

    $sql = "DELETE FROM degree WHERE Degree_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $Degree_ID);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&status=deleted");
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