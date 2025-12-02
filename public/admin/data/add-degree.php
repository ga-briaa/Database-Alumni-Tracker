<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Degree_ID = $_POST['degree-id'];
    $Degree_Abbreviation = $_POST['degree-abbreviation'];
    $Degree_Name = $_POST['degree-name'];

    // Check if Degree_ID already exists
    $checkSql = "SELECT COUNT(*) FROM degree WHERE Degree_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $Degree_ID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_row();
    $checkStmt->close();

    if ($row[0] > 0) {
        // Duplicate ID found
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&error=duplicate");
        exit();
    }

    $sql = "INSERT INTO degree (Degree_ID, Degree_Abbreviation, Degree_Name)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $Degree_ID, $Degree_Abbreviation, $Degree_Name);

    if ($stmt->execute()) {
        // Success
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&add=success");
    } else {
        // Error
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=degree&add=error");
        // echo "Error adding record: " . $stmt->error; // For debugging
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>