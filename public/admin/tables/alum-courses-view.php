<?php
    $rowPerPage = 10; // Number of rows to display per page

    // Columns that can be sorted
    $allowed_columns = [
        'name' => 'alumni.Alum_LastName',
        'degree' => 'graduation.Program_ID',
        'year' => 'graduation.Grad_Year'
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
                        OR graduation.Degree_ID LIKE ? 
                        OR graduation.Program_ID LIKE ? 
                        OR graduation.Grad_Year LIKE ?)";
        
        $search_params = [$search_like, $search_like, $search_like, $search_like, $search_like, $search_like];
        $search_param_types = "ssssss";
    }

    $countSql = "SELECT COUNT(*) FROM graduation
             INNER JOIN alumni ON graduation.Alum_ID = alumni.Alum_ID
             INNER JOIN program ON graduation.Program_ID = program.Program_ID"
             . $search_sql;
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

    $sql = "SELECT 
                *
            FROM 
                graduation
            INNER JOIN 
                alumni ON graduation.Alum_ID = alumni.Alum_ID
            INNER JOIN
                program ON graduation.Program_ID = program.Program_ID"
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
                    <th colspan='4' class='table-header'>Alumni Courses</th>
                </tr>
                <tr>
                    <th>
                        <a href='<?php echo "$current_table_url&sort=name&order=" . (($sort_column_key == 'name') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'name') echo "active-sort $sort_order"; ?>'>
                        Alumni
                        </a>
                    </th>
                    <th>
                        <a href='<?php echo "$current_table_url&sort=degree&order=" . (($sort_column_key == 'degree') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'degree') echo "active-sort $sort_order"; ?>'>
                        Degree
                        </a>
                    </th>
                    <th>
                        <a href='<?php echo "$current_table_url&sort=year&order=" . (($sort_column_key == 'year') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'year') echo "active-sort $sort_order"; ?>'>
                        Year
                        </a>
                    </th>
                    <th>Manage</th>
                </tr>
        <?php
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Alum_LastName']) . ", " . htmlspecialchars($row['Alum_FirstName']) . "<br><small>" . htmlspecialchars($row['Alum_ID']) . "</small></td>
                    <td>" . htmlspecialchars($row['Degree_ID']) . " " . htmlspecialchars($row['Program_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Grad_Year']) . "</td>
                    <td class='manage-icon-cell'>
            
                        <button class='btn-icon btn-modal-trigger' 
                                data-target='editModal-courses'
                                data-id='" . htmlspecialchars($row['Alum_ID']) . "'
                                data-firstName='" . htmlspecialchars($row['Alum_FirstName']) . "'
                                data-lastName='" . htmlspecialchars($row['Alum_LastName']) . "'
                                data-degree='" . htmlspecialchars($row['Degree_ID']) . "'
                                data-program='" . htmlspecialchars($row['Program_ID']) . "'
                                data-gradYear='" . htmlspecialchars($row['Grad_Year']) . "'
                                data-grad-id='" . htmlspecialchars($row['Grad_ID']) . "'>
                            
                            <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                            <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                        </button>

            
                        <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-grad-id='" . htmlspecialchars($row['Grad_ID']) . "'>
                            
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

<div class="modal" id="editModal-courses">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-form" action="<?php echo BASE_URL; ?>admin/data/update-alum-courses.php" method="POST">
            <div class="modal-body modal-form-grid">
                <input type="hidden" id="edit-alum-old-id" name="alum-old-id" value="">
                <input type="hidden" id="edit-grad-id" name="grad-id" value="">
                
                <label for="edit-alum-id">ID:</label>
                <input type="text" id="edit-alum-id" name="alum-id" value=""
                   required
                   pattern="\d{4}-\d{5}"
                   title="ID must be in the format XXXX-XXXXX (e.g., 2025-12345)" maxlength="10" class='modal-input-field'>
 
                
                <label for="edit-alum-firstName">First Name:</label>
                <input type="text" id="edit-alum-firstName" name="alum-firstName" value=""
                    required
                    maxlength="50" class='modal-input-field'>
                
                <label for="edit-alum-lastName">Last Name:</label>
                <input type="text" id="edit-alum-lastName" name="alum-lastName" value=""
                    required
                    maxlength="50" class='modal-input-field'>
 
                <label for="edit-degree-id">Degree:</label>
                <select id="edit-degree-id" name="degree-id" class='modal-input-field'>
                    <?php
                    foreach ($all_degrees as $degree) {
                        echo "<option value='" . htmlspecialchars($degree['Degree_ID']) . "'>"
                            . htmlspecialchars($degree['Degree_Name'])
                            . "</option>";
                    }
                    ?>
                </select>
 
                <label for="edit-program-id">Program:</label>
                <select id="edit-program-id" name="program-id" class='modal-input-field'>
                    <?php
                    foreach ($all_programs as $program) {
                        echo "<option value='" . htmlspecialchars($program['Program_ID']) . "'>"
                            . htmlspecialchars($program['Program_Name'])
                            . "</option>";
                    }
                    ?>
                </select>
                
                <label for="edit-grad-year">Grad Year:</label>
                <select id="edit-grad-year" name="grad-year" required class='modal-input-field'>
                    <?php
                    $currentYear = (int)date("Y");
                    $earliestYear = 1970; // or the earliest graduation year you want
 
                    for ($year = $currentYear; $year >= $earliestYear; $year--) {
                        echo "<option value='$year'>$year</option>";
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
        <form id="delete-form" action="<?php echo BASE_URL; ?>admin/data/delete-alum-courses.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <input type="hidden" id="delete-grad-id" name="grad-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>