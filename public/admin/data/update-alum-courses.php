<?php
    require_once '../../../src/database-config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Alum_ID_new = $_POST['alum-id'];
        $Alum_ID_old = $_POST['alum-old-id'];
        $Alum_FirstName = $_POST['alum-firstName'];
        $Alum_LastName = $_POST['alum-lastName'];
        $Degree_ID = $_POST['degree-id'];
        $Program_ID = $_POST['program-id'];
        $Grad_Year = $_POST['grad-year'];
        $Grad_ID = $_POST['grad-id'];

        if (!preg_match('/^\d{4}-\d{5}$/', $Alum_ID_new)) {
            die("Error: Invalid ID format.");
        }   

        $conn->begin_transaction();
        $update_success = true;

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
            $sql_graduation = "UPDATE graduation 
                               SET Degree_ID = ?, 
                                   Program_ID = ?, 
                                   Grad_Year = ? 
                               WHERE Grad_ID = ?";
            $stmt_graduation = $conn->prepare($sql_graduation);
            $stmt_graduation->bind_param("ssss", $Degree_ID, $Program_ID, $Grad_Year, $Grad_ID);
            
            if (!$stmt_graduation->execute()) {
                $update_success = false;
                $error_graduation = $stmt_graduation->error;
            }
            $stmt_graduation->close();
        }

        if ($update_success) {
            $conn->commit();
            header("Location: " . BASE_URL . "admin/database-manage.php?view-table=alumni-courses&update=success");
        } else {
            $conn->rollback();
            echo "Error updating record. Alumni update error: " . ($error_alumni ?? 'None') . 
                 ". Graduation update error: " . ($error_graduation ?? 'None');
        }

        $conn->close();
        
    } else {
        echo "Invalid request.";
    }
?>