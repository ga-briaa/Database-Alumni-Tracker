<?php
include '../../src/database-config.php';

// Fetch all statuses for the dropdown
$status_sql = "SELECT Status_ID, Status_Name FROM status ORDER BY Status_Name";
$status_result = $conn->query($status_sql);
$all_statuses = [];
if ($status_result->num_rows > 0) {
    while($status_row = $status_result->fetch_assoc()) {
        $all_statuses[] = $status_row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../src/templates/head.php'; ?>
        <title>DU - Database</title>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/database-manage.css">
    </head>

    <body>
        <!-- Header -->
        <?php include '../../src/templates/header.php'; ?>

        <div class="card-container">
            <div class="btn-selectors">
                <div class="select-table">
                    <button class="myBtn btn-modal-trigger" data-target="selectModal">Select Table</button>
    
                    <div class="modal" id="selectModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Select Table</h2>
                                <span class="close">&times;</span>
                            </div>
                            <div class="modal-body">
                                <form id="table-select-form" action="" method="GET">
                                    <p>Select Database</p>
    
                                    <div class="radio-options">
                                        <div class="radio-option-item">
                                            <input type="radio" id="alumni-info" name="view-table" value="alumni-info">
                                            <label for="alumni-info">Alumni Information</label><br>
                                        </div>
                                        <div class="radio-option-item">
                                            <input type="radio" id="alumni-courses" name="view-table" value="alumni-courses">
                                            <label for="alumni-courses">Alumni's Courses</label>
                                        </div>
                                        <div class="radio-option-item">
                                            <input type="radio" id="alumni-employment" name="view-table" value="alumni-employment">
                                            <label for="alumni-employment">Alumni's Employment</label><br>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn-apply" type="submit" form="table-select-form">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
    
                
                <?php
                // Button for filtering table only shows when a table is selected
                if(isset($_GET['view-table'])) {
                    echo "
                    <div class='filter-table'>
                        <button class='myBtn btn-modal-trigger' data-target='filterModal'>Filter Table</button>
        
                        <div class='modal' id='filterModal'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h2>Filter Table</h2>
                                    <span class='close'>&times;</span>
                                </div>
                                <div class='modal-body'>
                                    <form id='table-filter-form' action='' method='GET'>
                                        <p>Filter Database</p>
                                        <!-- Filter options can be added here -->
                                    </form>
                                </div>
                                <div class='modal-footer'>
                                    <button class='btn-apply' type='submit' form='table-filter-form'>Apply</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                    ?>

                <div class="add-data">
                    <button class="myBtn btn-modal-trigger" data-target="addModal">+ Add Data</button>

                    <div class="modal" id="addModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Add New Alumni</h2>
                                <span class="close">&times;</span>
                            </div>
                            <form id="add-form" action="<?php echo BASE_URL; ?>admin/data/add-alum-info.php" method="POST">
                                <div class="modal-body">

                                    <label for="add-alum-id">ID:</label>
                                    <input type="text" id="add-alum-id" name="alum-id"
                                        required
                                        pattern="\d{4}-\d{5}"
                                        title="ID must be in the format NNNN-NNNNN (e.g., 2025-12345)">

                                    <label for="add-alum-firstName">First Name:</label>
                                    <input type="text" id="add-alum-firstName" name="alum-firstName"
                                        required maxlength="50">

                                    <label for="add-alum-lastName">Last Name:</label>
                                    <input type="text" id="add-alum-lastName" name="alum-lastName"
                                        required maxlength="50">

                                    <label for="add-alum-contactInfo">Email:</label>
                                    <input type="email" id="add-alum-contactInfo" name="alum-contactInfo"
                                        required maxlength="100">

                                    <label for="add-alum-status">Status:</label>
                                    <select id="add-alum-status" name="alum-status">
                                        <?php
                                        // This works now because $all_statuses is global
                                        foreach ($all_statuses as $status) {
                                            echo "<option value='" . htmlspecialchars($status['Status_ID']) . "'>" 
                                                . htmlspecialchars($status['Status_Name']) 
                                                . "</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn-apply" type="submit" form="add-form">Add Alumni</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-display">
                <?php
                // Check if user has selected a table
                if(isset($_GET['view-table'])) {
                    $selected_table = $_GET['view-table'];
                    
                    // Redirect to the corresponding page based on selection
                    if($selected_table == 'alumni-info') {
                        include 'tables/alum-info-view.php';
                    } elseif($selected_table == 'alumni-courses') {
                        include 'tables/alum-courses-view.php';
                    } elseif($selected_table == 'alumni-employment') {
                        include 'tables/alum-employment-view.php';
                    }

                    // Pagination Links
                    if (isset($totalPages) && $totalPages > 1) {
                        echo '<div class="pagination">';
                        
                        // Previous Button
                        if ($currentPage > 1) {
                            $prevPage = $currentPage - 1;
                            echo "<a href='?view-table=$selected_table&page=$prevPage'>&lt;</a>";
                        }

                        // Page Number Links
                        for ($i = 1; $i <= $totalPages; $i++) {
                            // Check if $i is the current page
                            $activeClass = ($i == $currentPage) ? 'class="active"' : '';
                            echo "<a href='?view-table=$selected_table&page=$i' $activeClass>$i</a>";
                        }

                        // Next Button
                        if ($currentPage < $totalPages) {
                            $nextPage = $currentPage + 1;
                            echo "<a href='?view-table=$selected_table&page=$nextPage'>&gt;</a>";
                        }

                        echo '</div>';
                    }
                } else {
                    echo "<p>Please select a table to view its contents.</p>";
                }
                ?>
            </div>
        </div>


        <!-- Footer -->
        <?php include '../../src/templates/footer.php'; ?>
        
        <!-- Scripts -->
        <script src="<?php echo BASE_URL; ?>js/modal-popup.js"></script>
    </body>
</html>