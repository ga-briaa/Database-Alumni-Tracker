<?php
    require_once '../../../src/database-config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Alum_ID_new = trim($_POST['alum-id']);
        $Alum_ID_old = trim($_POST['alum-old-id']);
        $Alum_FirstName = trim($_POST['alum-firstName']);
        $Alum_LastName = trim($_POST['alum-lastName']);
        $Degree_ID = trim($_POST['degree-id']);
        $Program_ID = trim($_POST['program-id']);
        $Grad_Year = trim($_POST['grad-year']);
        $Grad_ID = trim($_POST['grad-id']);

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
                header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&error=duplicate");
                exit();
            }
        }

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

        if ($update_success) {
            // Get current graduation data for comparison
            $getGradSql = "SELECT Alum_ID, Degree_ID, Program_ID, Grad_Year FROM graduation WHERE Grad_ID = ?";
            $getGradStmt = $conn->prepare($getGradSql);
            $getGradStmt->bind_param("s", $Grad_ID);
            $getGradStmt->execute();
            $currentGradResult = $getGradStmt->get_result();
            $currentGradData = $currentGradResult->fetch_assoc();
            $getGradStmt->close();

            $oldAlumID = $currentGradData['Alum_ID'];
            $oldDegreeID = $currentGradData['Degree_ID'];
            $oldProgramID = $currentGradData['Program_ID'];
            $oldGradYear = $currentGradData['Grad_Year'];

            // Check if the new combination for graduation already exists and is different from the old
            if (
                ($Alum_ID_new !== $oldAlumID ||
                 $Degree_ID !== $oldDegreeID ||
                 $Program_ID !== $oldProgramID ||
                 $Grad_Year !== $oldGradYear)
            ) {
                $checkGradSql = "SELECT COUNT(*) FROM graduation WHERE Alum_ID = ? AND Degree_ID = ? AND Program_ID = ? AND Grad_Year = ? AND Grad_ID != ?";
                $checkGradStmt = $conn->prepare($checkGradSql);
                $checkGradStmt->bind_param("sssss", $Alum_ID_new, $Degree_ID, $Program_ID, $Grad_Year, $Grad_ID);
                $checkGradStmt->execute();
                $gradResult = $checkGradStmt->get_result();
                $gradRow = $gradResult->fetch_row();
                $checkGradStmt->close();

                if ($gradRow[0] > 0) {
                    $conn->rollback();
                    header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&error=duplicate");
                    exit();
                }
            }

            $sql_graduation = "UPDATE graduation
                               SET Alum_ID = ?,
                                   Degree_ID = ?,
                                   Program_ID = ?,
                                   Grad_Year = ?
                               WHERE Grad_ID = ?";
            $stmt_graduation = $conn->prepare($sql_graduation);
            $stmt_graduation->bind_param("sssss", $Alum_ID_new, $Degree_ID, $Program_ID, $Grad_Year, $Grad_ID);
            
            if (!$stmt_graduation->execute()) {
                $update_success = false;
                $error_graduation = $stmt_graduation->error;
            }
            $stmt_graduation->close();
        }

        if ($update_success) {
            $conn->commit();
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&update=success");
            exit();
        } else {
            $conn->rollback();
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&update=error");
            exit();
            // echo "Error updating record. Alumni update error: " . ($error_alumni ?? 'None') .
            //      ". Graduation update error: " . ($error_graduation ?? 'None'); // For debugging
        }
        $conn->close();
        
    } else {
        echo "Invalid request.";
    }
?>