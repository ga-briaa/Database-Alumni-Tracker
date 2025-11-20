<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'alumni.Alum_ID',
    'name' => 'alumni.Alum_LastName',
    'email' => 'alumni.Alum_ContactInfo',
    'status' => 'status.Status_Name'
];

$sort_column_key = isset($_GET['sort']) && array_key_exists($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'name';
$sort_column = $allowed_columns[$sort_column_key];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Search function
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = "";
$search_params = [];
$search_param_types = "";

if (!empty($search_term)) {
    $search_like = "%" . $search_term . "%";
    $search_sql = " WHERE (alumni.Alum_ID LIKE ? 
                      OR alumni.Alum_FirstName LIKE ? 
                      OR alumni.Alum_LastName LIKE ? 
                      OR alumni.Alum_ContactInfo LIKE ? 
                      OR status.Status_Name LIKE ?)";
    
    $search_params = [$search_like, $search_like, $search_like, $search_like, $search_like];
    $search_param_types = "sssss";
}

$countSql = "SELECT COUNT(*) FROM alumni 
             INNER JOIN status ON alumni.Status_ID = status.Status_ID" . $search_sql;
$stmt_count = $conn->prepare($countSql);

if (!empty($search_params)) {
    $stmt_count->bind_param($search_param_types, ...$search_params);
}

$stmt_count->execute();
$totalRows = $stmt_count->get_result()->fetch_row()[0];
$totalPages = ceil($totalRows / $rowPerPage);

if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

$startRow = ($currentPage - 1) * $rowPerPage;

$sql = "SELECT * FROM alumni 
        INNER JOIN status ON alumni.Status_ID = status.Status_ID"
       . $search_sql // Add the WHERE clause
       . " ORDER BY $sort_column $sort_order LIMIT ?, ?";

$stmt = $conn->prepare($sql);

$all_params = $search_params;
$all_param_types = $search_param_types;

$all_params[] = $startRow;
$all_params[] = $rowPerPage;
$all_param_types .= "ii";

$stmt->bind_param($all_param_types, ...$all_params);
$stmt->execute();
$result = $stmt->get_result();

$next_order = ($sort_order == 'ASC') ? 'DESC' : 'ASC';
$current_table_url = "?view-table=" . htmlspecialchars($_GET['view-table']);
$search_url_param = "&search=" . htmlspecialchars($search_term);

if($result->num_rows > 0) {
?>
    <table>
            <tr>
                <th colspan='5' class='table-header'>Alumni Information</th>
            </tr>
            <tr>
                <th>
                    <a href='<?php echo "$current_table_url&sort=id&order=" . (($sort_column_key == 'id') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'id') echo "active-sort $sort_order"; ?>'>
                    ID
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=name&order=" . (($sort_column_key == 'name') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'name') echo "active-sort $sort_order"; ?>'>
                    Name
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=email&order=" . (($sort_column_key == 'email') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'email') echo "active-sort $sort_order"; ?>'>
                    Email
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=status&order=" . (($sort_column_key == 'status') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'status') echo "active-sort $sort_order"; ?>'>
                    Status
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Alum_ID']) . "</td>
                <td>" . htmlspecialchars($row['Alum_LastName']) . ", " . htmlspecialchars($row['Alum_FirstName']) . "</td>
                <td>" . htmlspecialchars($row['Alum_ContactInfo']) . "</td>
                <td>" . htmlspecialchars($row['Status_Name']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-info'
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
    if (!empty($search_term)) {
        echo "<p>No records found matching your search for '" . htmlspecialchars($search_term) . "'.</p>";
    } else {
        echo "<p>No records found.</p>";
    }
}
?>

<div class="modal" id="editModal-info">
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