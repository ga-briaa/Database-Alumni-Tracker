<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'Status_ID',
    'name' => 'Status_Name'
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
    $search_sql = " WHERE (Status_ID LIKE ? 
                      OR Status_Name LIKE ?)";
    
    $search_params = [$search_like, $search_like];
    $search_param_types = "ss";
}

$countSql = "SELECT COUNT(*) FROM status" . $search_sql;
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

$sql = "SELECT Status_ID, Status_Name FROM status"
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
                <th colspan='3' class='table-header'>Status Information</th>
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
                    Status Name
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Status_ID']) . "</td>
                <td>" . htmlspecialchars($row['Status_Name']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-status'
                            data-id='" . htmlspecialchars($row['Status_ID']) . "'
                            data-name='" . htmlspecialchars($row['Status_Name']) . "'>
                        
                        <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                        <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                    </button>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-id='" . htmlspecialchars($row['Status_ID']) . "'>
                        
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

<div class="modal" id="editModal-status">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Status Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-status-form" action="<?php echo BASE_URL; ?>admin/data/update-status.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-status-old-id" name="status-old-id" value="">
                
                <label for="edit-status-id">Status ID:</label>
                <input type="text" id="edit-status-id" name="status-id" value=""
                   required
                   maxlength="2" class='modal-input-field'>
 
                
                <label for="edit-status-name">Status Name:</label>
                <input type="text" id="edit-status-name" name="status-name" value=""
                    required
                    maxlength="20" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-status-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="addModal-status">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Status Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="add-status-form" action="<?php echo BASE_URL; ?>admin/data/add-status.php" method="POST">
            <div class="modal-body modal-form-grid">
                <label for="add-status-id">Status ID:</label>
                <input type="text" id="add-status-id" name="status-id" value=""
                   required
                   maxlength="2" class='modal-input-field'>
                
                <label for="add-status-name">Status Name:</label>
                <input type="text" id="add-status-name" name="status-name" value=""
                    required
                    maxlength="20" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="add-status-form">Add Status</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Status Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-status-form" action="<?php echo BASE_URL; ?>admin/data/delete-status.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-status-id" name="status-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-status-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>