<?php
    $rowPerPage = 10; // Number of rows to display per page

    // Columns that can be sorted
    $allowed_columns = [
        'id' => 'alumni.Alum_ID',
        'name' => 'alumni.Alum_LastName',
        'position' => 'job_position.Position_Name',
        'company' => 'company.Company_Name',
        'start_date' => 'employment.Start_Date'
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
                        OR job_position.Position_Name LIKE ? 
                        OR company.Company_Name LIKE ? 
                        OR location.City LIKE ?
                        OR location.Country LIKE ?)";
        
        $search_params = [$search_like, $search_like, $search_like, $search_like, $search_like, $search_like, $search_like];
        $search_param_types = "sssssss";
    }

    // Count Query
    $countSql = "SELECT COUNT(*) FROM employment 
                 INNER JOIN alumni ON employment.Alum_ID = alumni.Alum_ID
                 INNER JOIN job_position ON employment.Position_ID = job_position.Position_ID
                 INNER JOIN company ON employment.Company_ID = company.Company_ID
                 INNER JOIN `location` ON employment.Location_ID = location.Location_ID"
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

    // Main Data Query
    $sql = "SELECT 
                employment.Employment_ID,
                employment.Start_Date,
                employment.End_Date,
                employment.Alum_ID,
                employment.Position_ID,
                employment.Company_ID,
                employment.Location_ID,
                alumni.Alum_FirstName,
                alumni.Alum_LastName,
                job_position.Position_Name,
                company.Company_Name,
                location.City,
                location.Country
            FROM 
                employment 
            INNER JOIN 
                alumni ON employment.Alum_ID = alumni.Alum_ID
            INNER JOIN
                job_position ON employment.Position_ID = job_position.Position_ID
            INNER JOIN
                company ON employment.Company_ID = company.Company_ID
            INNER JOIN
                `location` ON employment.Location_ID = location.Location_ID"
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
                    <th colspan='6' class='table-header'>Alumni Employment</th>
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
                        <a href='<?php echo "$current_table_url&sort=position&order=" . (($sort_column_key == 'position') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'position') echo "active-sort $sort_order"; ?>'>
                        Position
                        </a>
                    </th>
                    <th>
                        <a href='<?php echo "$current_table_url&sort=company&order=" . (($sort_column_key == 'company') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'company') echo "active-sort $sort_order"; ?>'>
                        Company & Location
                        </a>
                    </th>
                    <th>
                         <a href='<?php echo "$current_table_url&sort=start_date&order=" . (($sort_column_key == 'start_date') ? $next_order : 'ASC') . $search_url_param; ?>'
                        class='<?php if($sort_column_key == 'start_date') echo "active-sort $sort_order"; ?>'>
                        Dates
                        </a>
                    </th>
                    <th>Manage</th>
                </tr>
        <?php
        while($row = $result->fetch_assoc()) {
            // Handle logic to display dates gracefully
            $endDate = $row['End_Date'] ? $row['End_Date'] : 'Present';
            
            echo "<tr>
                    <td>" . htmlspecialchars($row['Alum_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Alum_LastName']) . ", " . htmlspecialchars($row['Alum_FirstName']) . "</td>
                    <td>" . htmlspecialchars($row['Position_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Company_Name']) . "<br><small>" . htmlspecialchars($row['City']) . ", " . htmlspecialchars($row['Country']) . "</small></td>
                    <td>" . htmlspecialchars($row['Start_Date']) . " to <br>" . htmlspecialchars($endDate) . "</td>
                    <td class='manage-icon-cell'>
            
                        <button class='btn-icon btn-modal-trigger' 
                                data-target='editModal-employment'
                                data-emp-id='" . htmlspecialchars($row['Employment_ID']) . "' 
                                data-id='" . htmlspecialchars($row['Alum_ID']) . "'
                                data-firstName='" . htmlspecialchars($row['Alum_FirstName']) . "'
                                data-lastName='" . htmlspecialchars($row['Alum_LastName']) . "'
                                data-position='" . htmlspecialchars($row['Position_ID']) . "'
                                data-company='" . htmlspecialchars($row['Company_ID']) . "'
                                data-location='" . htmlspecialchars($row['Location_ID']) . "'
                                data-startDate='" . htmlspecialchars($row['Start_Date']) . "'
                                data-endDate='" . htmlspecialchars($row['End_Date']) . "'>
                            
                            <img class='img-default' src='" . BASE_URL . "assets/pencil-grey.png' alt='Edit'>
                            <img class='img-hover' src='" . BASE_URL . "assets/pencil-yellow.png' alt='Edit'>
                        </button>

            
                        <button class='btn-icon btn-modal-trigger' 
                            data-target='deleteModal'
                            data-emp-id='" . htmlspecialchars($row['Employment_ID']) . "'>
                            
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

<div class="modal" id="editModal-employment">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Employment Data</h2>
            <span class="close">&times;</span>
        </div>
        <form id="edit-form" action="<?php echo BASE_URL; ?>admin/data/update-alum-employment.php" method="POST">
            <div class="modal-body">
                <input type="hidden" id="edit-emp-id" name="emp-id" value="">
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

                <label for="edit-position-id">Position:</label>
                <select id="edit-position-id" name="position-id">
                    <?php
                    // Ensure $all_positions is populated from database before this view is loaded
                    if(isset($all_positions)){
                        foreach ($all_positions as $position) {
                            echo "<option value='" . htmlspecialchars($position['Position_ID']) . "'>" 
                                . htmlspecialchars($position['Position_Name']) 
                                . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="edit-company-id">Company:</label>
                <select id="edit-company-id" name="company-id">
                    <?php
                    // Ensure $all_companies is populated from database
                     if(isset($all_companies)){
                        foreach ($all_companies as $company) {
                            echo "<option value='" . htmlspecialchars($company['Company_ID']) . "'>" 
                                . htmlspecialchars($company['Company_Name']) 
                                . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="edit-location-id">Location:</label>
                <select id="edit-location-id" name="location-id">
                    <?php
                    // Ensure $all_locations is populated from database
                     if(isset($all_locations)){
                        foreach ($all_locations as $location) {
                            echo "<option value='" . htmlspecialchars($location['Location_ID']) . "'>" 
                                . htmlspecialchars($location['City'] . ", " . $location['Country']) 
                                . "</option>";
                        }
                    }
                    ?>
                </select>
                
                <label for="edit-start-date">Start Date:</label>
                <input type="date" id="edit-start-date" name="start-date" required>

                <label for="edit-end-date">End Date:</label>
                <div class="input-wrapper">
                    <input type="date" id="edit-end-date" name="end-date">
                    <span class="input-hint">Leave empty if Current</span>
                </div>

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
        <form id="delete-form" action="<?php echo BASE_URL; ?>admin/data/delete-alum-employment.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this employment record?</p>
                <input type="hidden" id="delete-emp-id" name="emp-id" value="">
            </div>
            <div class="modal-footer">
                <button class="btn-apply" type="submit" form="delete-form">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>