<?php
require_once '../../../src/database-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Employment_ID = trim($_POST['emp-id']); // Primary key for employment table
    
    // Alumni Table Data
    $Alum_ID_new = trim($_POST['alum-id']);
    $Alum_ID_old = trim($_POST['alum-old-id']);
    $Alum_FirstName = trim($_POST['alum-firstName']);
    $Alum_LastName = trim($_POST['alum-lastName']);
    
    // Employment Table Data
    $Position_ID = trim($_POST['position-id']);
    $Company_ID = trim($_POST['company-id']);
    $Location_ID = trim($_POST['location-id']);
    $Start_Date = trim($_POST['start-date']);
    $End_Date = !empty($_POST['end-date']) ? trim($_POST['end-date']) : NULL;

    if (!preg_match('/^\d{4}-\d{5}$/', $Alum_ID_new)) {
        die("Error: Invalid ID format.");
    }

    $conn->begin_transaction();
    $update_success = true;

    // Check for duplicate Alum_ID in alumni table if Alum_ID is changed
    // The check should ensure that the new Alum_ID does not exist for any *other* alumni record.
    if ($Alum_ID_new !== $Alum_ID_old) {
        $checkAlumSql = "SELECT COUNT(*) FROM alumni WHERE Alum_ID = ? AND Alum_ID != ?";
        $checkAlumStmt = $conn->prepare($checkAlumSql);
        $checkAlumStmt->bind_param("ss", $Alum_ID_new, $Alum_ID_old); // Check if Alum_ID_new exists, excluding the current record's old ID
        $checkAlumStmt->execute();
        $alumResult = $checkAlumStmt->get_result();
        $alumRow = $alumResult->fetch_row();
        $checkAlumStmt->close();

        if ($alumRow[0] > 0) {
            // Duplicate ID found for another record
            $conn->rollback();
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&error=duplicate");
            exit();
        }
    }

    // 1. Update Alumni Information (ID and Name)
    $sql_alumni = "UPDATE alumni
                   SET Alum_ID = ?,
                       Alum_FirstName = ?,
                       Alum_LastName = ?
                   WHERE Alum_ID = ?";
    $stmt_alumni = $conn->prepare($sql_alumni);
    $stmt_alumni->bind_param("ssss", $Alum_ID_new, $Alum_FirstName, $Alum_LastName, $Alum_ID_old);
    
    if (!$stmt_alumni->execute()) {
        $update_success = false;
        $error_alumni = $stmt_alumni->error;
    }
    $stmt_alumni->close();

    // 2. Update Employment Record
    if ($update_success) {
        // Get current employment data for comparison
        $getEmpSql = "SELECT Alum_ID, Position_ID, Company_ID, Location_ID, Start_Date FROM employment WHERE Employment_ID = ?";
        $getEmpStmt = $conn->prepare($getEmpSql);
        $getEmpStmt->bind_param("i", $Employment_ID);
        $getEmpStmt->execute();
        $currentEmpResult = $getEmpStmt->get_result();
        $currentEmpData = $currentEmpResult->fetch_assoc();
        $getEmpStmt->close();

        $oldAlumID_emp = $currentEmpData['Alum_ID'];
        $oldPositionID = $currentEmpData['Position_ID'];
        $oldCompanyID = $currentEmpData['Company_ID'];
        $oldLocationID = $currentEmpData['Location_ID'];
        $oldStartDate = $currentEmpData['Start_Date'];

        // Check if the new combination for employment already exists and is different from the old
        if (
            ($Alum_ID_new !== $oldAlumID_emp ||
             $Position_ID !== $oldPositionID ||
             $Company_ID !== $oldCompanyID ||
             $Location_ID !== $oldLocationID ||
             $Start_Date !== $oldStartDate)
        ) {
            $checkEmpSql = "SELECT COUNT(*) FROM employment WHERE Alum_ID = ? AND Position_ID = ? AND Company_ID = ? AND Location_ID = ? AND Start_Date = ? AND Employment_ID != ?";
            $checkEmpStmt = $conn->prepare($checkEmpSql);
            $checkEmpStmt->bind_param("sssssi", $Alum_ID_new, $Position_ID, $Company_ID, $Location_ID, $Start_Date, $Employment_ID);
            $checkEmpStmt->execute();
            $empResult = $checkEmpStmt->get_result();
            $empRow = $empResult->fetch_row();
            $checkEmpStmt->close();

            if ($empRow[0] > 0) {
                $conn->rollback();
                header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&error=duplicate");
                exit();
            }
        }

        $sql_employment = "UPDATE employment
                           SET Alum_ID = ?,
                               Position_ID = ?,
                               Company_ID = ?,
                               Location_ID = ?,
                               Start_Date = ?,
                               End_Date = ?
                           WHERE Employment_ID = ?";
        $stmt_employment = $conn->prepare($sql_employment);
        $stmt_employment->bind_param("ssssssi", $Alum_ID_new, $Position_ID, $Company_ID, $Location_ID, $Start_Date, $End_Date, $Employment_ID);
        
        if (!$stmt_employment->execute()) {
            $update_success = false;
            $error_employment = $stmt_employment->error;
        }
        $stmt_employment->close();
    }

    // 3. Commit or Rollback
    if ($update_success) {
        $conn->commit();
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&update=success");
        exit();
    } else {
        $conn->rollback();
        header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-employment&update=error");
        exit();
        // echo "Error updating record. Alumni update error: " . ($error_alumni ?? 'None') .
        //      ". Employment update error: " . ($error_employment ?? 'None'); // For debugging
    }

    $conn->close();
    
} else {
    echo "Invalid request.";
}
?>