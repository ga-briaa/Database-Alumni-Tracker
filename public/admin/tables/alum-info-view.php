<?php
    $rowPerPage = 10; // Number of rows to display per page

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    $countSql = "SELECT COUNT(*) FROM alumni";
    $countResult = $conn->query($countSql);
    $totalRows = $countResult->fetch_row()[0]; // Total number of rows in the table
    $totalPages = ceil($totalRows / $rowPerPage); // Calculate total pages for pagination

    // Stay within valid page range
    if ($currentPage > $totalPages && $totalPages > 0) {
        $currentPage = $totalPages;
    }

    $startRow = ($currentPage - 1) * $rowPerPage;

    $sql = "SELECT 
                * 
            FROM 
                alumni 
            INNER JOIN 
                status ON alumni.Status_ID = status.Status_ID
            ORDER BY 
                Alum_ID ASC LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $startRow, $rowPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
        
    if($result->num_rows > 0) {
        echo "<table>
        <tr>
            <th colspan='5' class='table-header'>Alumni Information</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Alum_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Alum_LastName']) . ", " . htmlspecialchars($row['Alum_FirstName']) . "</td>
                    <td>" . htmlspecialchars($row['Alum_ContactInfo']) . "</td>
                    <td>" . htmlspecialchars($row['Status_Name']) . "</td>
                    <td class='manage-icon-cell'>
            
                        <button class='btn-icon btn-modal-trigger' 
                                data-target='editModal'
                                data-id='" . htmlspecialchars($row['Alum_ID']) . "'
                                data-firstName='" . htmlspecialchars($row['Alum_FirstName']) . "'
                                data-lastName='" . htmlspecialchars($row['Alum_LastName']) . "'
                                data-email='" . htmlspecialchars($row['Alum_ContactInfo']) . "'
                                data-status='" . htmlspecialchars($row['Status_ID']) . "'>
                            
                            <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                            <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                        </button>

                        <button class='btn-icon btn-modal-trigger' 
                                data-target='deleteModal'
                                data-id='" . htmlspecialchars($row['Alum_ID']) . "'>
                            
                            <img class='img-default' src='" . BASE_URL . "assets/trash-can-grey.png' alt='Delete'>
                            <img class='img-hover' src='" . BASE_URL . "assets/trash-can-maroon.png' alt='Delete'>
                        </button>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
?>

<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-form" action="<?php echo BASE_URL; ?>admin/data/update-alum-info.php" method="POST">
            <div class="modal-body">
                <input type="hidden" id="edit-alum-old-id" name="alum-old-id" value="">
                
                <label for="edit-alum-id">ID:</label>
                <input type="text" id="edit-alum-id" name="alum-id" value=""
                   required
                   pattern="\d{4}-\d{5}"
                   title="ID must be in the format XXXX-XXXXX (e.g., 2025-12345)">

                
                <label for="edit-alum-firstName">First Name:</label>
                <input type="text" id="edit-alum-firstName" name="alum-firstName" value=""
                    required
                    maxlength="30">
                
                <label for="edit-alum-lastName">Last Name:</label>
                <input type="text" id="edit-alum-lastName" name="alum-lastName" value=""
                    required
                    maxlength="30">
                
                <label for="edit-alum-contactInfo">Email:</label>
                <input type="text" id="edit-alum-contactInfo" name="alum-contactInfo" value=""
                    required
                    maxlength="100">
                
                <label for="edit-alum-status">Status:</label>
                <select id="edit-alum-status" name="alum-status">
                    <?php
                    foreach ($all_statuses as $status) {
                        echo "<option value='" . htmlspecialchars($status['Status_ID']) . "'>" 
                            . htmlspecialchars($status['Status_Name']) 
                            . "</option>";
                    }
                ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-form" action="<?php echo BASE_URL; ?>admin/data/delete-alum-info.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-alum-id" name="alum-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>