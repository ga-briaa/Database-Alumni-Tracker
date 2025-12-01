<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'Company_ID',
    'name' => 'Company_Name'
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
    $search_sql = " WHERE (Company_ID LIKE ? 
                      OR Company_Name LIKE ?)";
    
    $search_params = [$search_like, $search_like];
    $search_param_types = "ss";
}

$countSql = "SELECT COUNT(*) FROM company" . $search_sql;
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

$sql = "SELECT Company_ID, Company_Name FROM company"
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
                <th colspan='3' class='table-header'>Company Information</th>
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
                    Company Name
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Company_ID']) . "</td>
                <td>" . htmlspecialchars($row['Company_Name']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-company'
                            data-id='" . htmlspecialchars($row['Company_ID']) . "'
                            data-name='" . htmlspecialchars($row['Company_Name']) . "'>
                        
                        <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                        <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                    </button>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-id='" . htmlspecialchars($row['Company_ID']) . "'>
                        
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

<div class="modal" id="editModal-company">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Company Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-company-form" action="<?php echo BASE_URL; ?>admin/data/update-company.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-company-old-id" name="company-old-id" value="">
                
                <label for="edit-company-id">Company ID:</label>
                <input type="number" step="1" min="1" max="999" id="edit-company-id" name="company-id" value=""
                   required class='modal-input-field'>
                
                <label for="edit-company-name">Company Name:</label>
                <input type="text" id="edit-company-name" name="company-name" value=""
                    required
                    maxlength="25" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-company-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="addModal-company">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Company Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="add-company-form" action="<?php echo BASE_URL; ?>admin/data/add-company.php" method="POST">
            <div class="modal-body modal-form-grid">
                <label for="add-company-id">Company ID:</label>
                <input type="number" step="1" min="1" max="999" id="add-company-id" name="company-id" value=""
                   required class='modal-input-field'>
                
                <label for="add-company-name">Company Name:</label>
                <input type="text" id="add-company-name" name="company-name" value=""
                    required
                    maxlength="25" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="add-company-form">Add Company</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Company Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-company-form" action="<?php echo BASE_URL; ?>admin/data/delete-company.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-company-id" name="company-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-company-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>