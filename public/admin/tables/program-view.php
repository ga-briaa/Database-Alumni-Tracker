<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'program.Program_ID',
    'name' => 'program.Program_Name',
    'college' => 'college.College_Name'
];

$sort_column_key = isset($_GET['sort']) && array_key_exists($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'name';
$sort_column = $allowed_columns[$sort_column_key];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Fetch all colleges for the dropdown
$college_sql = "SELECT College_ID, College_Name FROM college ORDER BY College_Name ASC";
$college_result = $conn->query($college_sql);
$all_colleges = [];
if ($college_result->num_rows > 0) {
    while ($college_row = $college_result->fetch_assoc()) {
        $all_colleges[] = $college_row;
    }
}

// Search function
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$filter_college = isset($_GET['program-college']) ? $_GET['program-college'] : '';

// WHERE clause for search and filter
$sql_where = "";
$where_clauses = [];
$params = [];
$types = "";

if (!empty($filter_college)) {
    $where_clauses[] = "program.College_ID = ?";
    $params[] = $filter_college;
    $types .= "s";
}

if (!empty($search_term)) {
    $search_like = "%" . $search_term . "%";
    $search_sql = " WHERE (program.Program_ID LIKE ? 
                      OR program.Program_Name LIKE ? 
                      OR college.College_Name LIKE ?)";
    
    $params[] = $search_like;
    $params[] = $search_like;
    $params[] = $search_like;
    $types .= "sss";
}

// Combine WHERE parameters
if (count($where_clauses) > 0) {
    $sql_where = " WHERE " . implode(" AND ", $where_clauses);
}

$countSql = "SELECT COUNT(*) FROM program 
             INNER JOIN college ON program.College_ID = college.College_ID" . $sql_where;
$stmt_count = $conn->prepare($countSql);

if (!empty($params)) {
    $stmt_count->bind_param($types, ...$params);
}

$stmt_count->execute();
$totalRows = $stmt_count->get_result()->fetch_row()[0];
$totalPages = ceil($totalRows / $rowPerPage);

if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

$startRow = ($currentPage - 1) * $rowPerPage;

$sql = "SELECT program.Program_ID, program.Program_Name, college.College_Name, college.College_ID FROM program 
        INNER JOIN college ON program.College_ID = college.College_ID"
       . $sql_where // Add the WHERE clause
       . " ORDER BY $sort_column $sort_order LIMIT ?, ?";

$params[] = $startRow;
$params[] = $rowPerPage;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$next_order = ($sort_order == 'ASC') ? 'DESC' : 'ASC';
$current_table_url = "?view-table=" . htmlspecialchars($_GET['view-table']);
$search_url_param = "&search=" . htmlspecialchars($search_term);

if($result->num_rows > 0) {
?>
    <table>
            <tr>
                <th colspan='4' class='table-header'>Program Information</th>
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
                    Program Name
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=college&order=" . (($sort_column_key == 'college') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'college') echo "active-sort $sort_order"; ?>'>
                    College
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Program_ID']) . "</td>
                <td>" . htmlspecialchars($row['Program_Name']) . "</td>
                <td>" . htmlspecialchars($row['College_Name']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-program'
                            data-id='" . htmlspecialchars($row['Program_ID']) . "'
                            data-name='" . htmlspecialchars($row['Program_Name']) . "'
                            data-college='" . htmlspecialchars($row['College_ID']) . "'>
                        
                        <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                        <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                    </button>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-id='" . htmlspecialchars($row['Program_ID']) . "'>
                        
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

<div class="modal" id="editModal-program">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Program Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-program-form" action="<?php echo BASE_URL; ?>admin/data/update-program.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-program-old-id" name="program-old-id" value="">
                
                <label for="edit-program-id">Program ID:</label>
                <input type="text" id="edit-program-id" name="program-id" value=""
                   required
                   maxlength="10" class='modal-input-field'>
 
                
                <label for="edit-program-name">Program Name:</label>
                <input type="text" id="edit-program-name" name="program-name" value=""
                    required
                    maxlength="40" class='modal-input-field'>
                
                <label for="edit-program-college">College:</label>
                <select id="edit-program-college" name="program-college" class='modal-input-field'>
                    <?php
                    foreach ($all_colleges as $college) {
                        echo "<option value='" . htmlspecialchars($college['College_ID']) . "'>"
                            . htmlspecialchars($college['College_Name'])
                            . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-program-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Program Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-program-form" action="<?php echo BASE_URL; ?>admin/data/delete-program.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-program-id" name="program-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-program-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>