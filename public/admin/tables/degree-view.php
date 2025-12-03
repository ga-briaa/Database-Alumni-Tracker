<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'Degree_ID',
    'abbreviation' => 'Degree_Abbreviation',
    'name' => 'Degree_Name'
];

$sort_column_key = isset($_GET['sort']) && array_key_exists($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'id';
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
    $search_sql = " WHERE (Degree_ID LIKE ? 
                      OR Degree_Abbreviation LIKE ?
                      OR Degree_Name LIKE ?)";
    
    $search_params = [$search_like, $search_like];
    $search_param_types = "ss";
}

$countSql = "SELECT COUNT(*) FROM degree" . $search_sql;
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

$sql = "SELECT Degree_ID, Degree_Abbreviation, Degree_Name FROM degree"
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
                <th colspan='4' class='table-header'>Degree Information</th>
            </tr>
            <tr>
                <th>
                    <a href='<?php echo "$current_table_url&sort=id&order=" . (($sort_column_key == 'id') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'id') echo "active-sort $sort_order"; ?>'>
                    ID
                    </a>
                </th>

                <th>
                    <a href='<?php echo "$current_table_url&sort=abbreviation&order=" . (($sort_column_key == 'abbreviation') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'abbreviation') echo "active-sort $sort_order"; ?>'>
                    Degree Abbreviation
                    </a>
                </th>

                <th>
                    <a href='<?php echo "$current_table_url&sort=name&order=" . (($sort_column_key == 'name') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'name') echo "active-sort $sort_order"; ?>'>
                    Degree Name
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Degree_ID']) . "</td>
                <td>" . htmlspecialchars($row['Degree_Abbreviation']) . "</td>
                <td>" . htmlspecialchars($row['Degree_Name']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-degree'
                            data-id='" . htmlspecialchars($row['Degree_ID']) . "'
                            data-abbreviation='" . htmlspecialchars($row['Degree_Abbreviation']) . "'
                            data-name='" . htmlspecialchars($row['Degree_Name']) . "'>
                        
                        <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                        <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                    </button>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-id='" . htmlspecialchars($row['Degree_ID']) . "'>
                        
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

<div class="modal" id="editModal-degree">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Degree Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-degree-form" action="<?php echo BASE_URL; ?>admin/data/update-degree.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-degree-old-id" name="degree-old-id" value="">
                
                <label for="edit-degree-id">Degree ID:</label>
                <input type="text" id="edit-degree-id" name="degree-id" value=""
                   required
                   maxlength="3" class='modal-input-field'>
                
                <label for="edit-degree-abbreviation">Degree Abbreviation:</label>
                <input type="text" id="edit-degree-abbreviation" name="degree-abbreviation" value=""
                    required
                    maxlength="15" class='modal-input-field'>
                
                <label for="edit-degree-name">Degree Name:</label>
                <input type="text" id="edit-degree-name" name="degree-name" value=""
                    required
                    maxlength="25" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-degree-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="addModal-degree">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Degree Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="add-degree-form" action="<?php echo BASE_URL; ?>admin/data/add-degree.php" method="POST">
            <div class="modal-body modal-form-grid">
                <label for="add-degree-id">Degree ID:</label>
                <input type="text" id="add-degree-id" name="degree-id" value=""
                   required
                   maxlength="3" class='modal-input-field'>
                
                <label for="add-degree-abbreviation">Degree Abbreviation:</label>
                <input type="text" id="add-degree-abbreviation" name="degree-abbreviation" value=""
                    required
                    maxlength="15" class='modal-input-field'>
                
                <label for="add-degree-name">Degree Name:</label>
                <input type="text" id="add-degree-name" name="degree-name" value=""
                    required
                    maxlength="25" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="add-degree-form">Add Degree</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Degree Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-degree-form" action="<?php echo BASE_URL; ?>admin/data/delete-degree.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-degree-id" name="degree-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-degree-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>