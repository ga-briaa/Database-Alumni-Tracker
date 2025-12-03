<?php
$rowPerPage = 10; // Number of rows to display per page

// Columns that can be sorted
$allowed_columns = [
    'id' => 'Location_ID',
    'country' => 'Country',
    'region' => 'Region',
    'city' => 'City',
];

$sort_column_key = isset($_GET['sort']) && array_key_exists($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'country';
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
    $search_sql = " WHERE (Country LIKE ?
                      OR Region LIKE ?
                      OR City LIKE ?)";
    
    $search_params = [$search_like, $search_like, $search_like];
    $search_param_types = "sss";
}

$countSql = "SELECT COUNT(*) FROM location" . $search_sql;
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

$sql = "SELECT Location_ID, Country, Region, City FROM location"
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
                <th colspan='4' class='table-header'>Location Information</th>
            </tr>
            <tr>
                <th>
                    <a href='<?php echo "$current_table_url&sort=country&order=" . (($sort_column_key == 'country') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'country') echo "active-sort $sort_order"; ?>'>
                    Country
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=region&order=" . (($sort_column_key == 'region') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'region') echo "active-sort $sort_order"; ?>'>
                    Region
                    </a>
                </th>
                <th>
                    <a href='<?php echo "$current_table_url&sort=city&order=" . (($sort_column_key == 'city') ? $next_order : 'ASC') . $search_url_param; ?>'
                    class='<?php if($sort_column_key == 'city') echo "active-sort $sort_order"; ?>'>
                    City
                    </a>
                </th>
                <th>Manage</th>
            </tr>
    <?php
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['Country']) . "</td>
                <td>" . htmlspecialchars($row['Region']) . "</td>
                <td>" . htmlspecialchars($row['City']) . "</td>
                <td class='manage-icon-cell'>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='editModal-location'
                            data-id='" . htmlspecialchars($row['Location_ID']) . "'
                            data-country='" . htmlspecialchars($row['Country']) . "'
                            data-region='" . htmlspecialchars($row['Region']) . "'
                            data-city='" . htmlspecialchars($row['City']) . "'>
                        
                        <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                        <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                    </button>
        
                    <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-id='" . htmlspecialchars($row['Location_ID']) . "'>
                        
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

<div class="modal" id="editModal-location">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Location Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-location-form" action="<?php echo BASE_URL; ?>admin/data/update-location.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-location-old-id" name="location-old-id" value="">
                
                <label for="edit-location-country">Country:</label>
                <input type="text" id="edit-location-country" name="location-country" value=""
                    required
                    maxlength="50" class='modal-input-field'>

                <label for="edit-location-region">Region:</label>
                <input type="text" id="edit-location-region" name="location-region" value=""
                    required
                    maxlength="50" class='modal-input-field'>
                
                <label for="edit-location-city">City:</label>
                <input type="text" id="edit-location-city" name="location-city" value=""
                    required
                    maxlength="50" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="edit-location-form">Apply Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="addModal-location">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Location Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="add-location-form" action="<?php echo BASE_URL; ?>admin/data/add-location.php" method="POST">
            <div class="modal-body modal-form-grid">
                <label for="add-location-country">Country:</label>
                <input type="text" id="add-location-country" name="location-country" value=""
                    required
                    maxlength="50" class='modal-input-field'>

                <label for="add-location-region">Region:</label>
                <input type="text" id="add-location-region" name="location-region" value=""
                    required
                    maxlength="50" class='modal-input-field'>

                <label for="add-location-city">City:</label>
                <input type="text" id="add-location-city" name="location-city" value=""
                    required
                    maxlength="50" class='modal-input-field'>
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="add-location-form">Add Location</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Location Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="delete-location-form" action="<?php echo BASE_URL; ?>admin/data/delete-location.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-location-id" name="location-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-location-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>