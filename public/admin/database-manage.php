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
// Fetch all degrees for the dropdown
$degree_sql = "SELECT Degree_ID, Degree_Name FROM degree ORDER BY Degree_Name";
$degree_result = $conn->query($degree_sql);
$all_degrees = [];
if ($degree_result->num_rows > 0) {
    while($degree_row = $degree_result->fetch_assoc()) {
        $all_degrees[] = $degree_row;
    }
}
// Fetch all programs for the dropdown
$program_sql = "SELECT Program_ID, Program_Name FROM program ORDER BY Program_Name";
$program_result = $conn->query($program_sql);
$all_programs = [];
if ($program_result->num_rows > 0) {
    while($program_row = $program_result->fetch_assoc()) {
        $all_programs[] = $program_row;
    }
}

// Fetch all alumni for the "Add Graduation" dropdown
$alumni_sql = "SELECT Alum_ID, Alum_FirstName, Alum_LastName FROM alumni ORDER BY Alum_LastName, Alum_FirstName";
$alumni_result = $conn->query($alumni_sql);
$all_alumni = [];
if ($alumni_result->num_rows > 0) {
    while($alumni_row = $alumni_result->fetch_assoc()) {
        $all_alumni[] = $alumni_row;
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
        <?php include '../../src/templates/header.php'; ?>

        <div class="card-container">
            <div class="btn-selectors">
                <div class="select-table">
                    <button class="myBtn btn-modal-trigger" data-target="selectModal">Select Table</button>
    
                    <div class="modal" id="selectModal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Select Database</h2>
                                <span class="close">&times;</span>
                            </div>
                            <div class="modal-body">
                                <form id="table-select-form" action="" method="GET">
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
                if(isset($_GET['view-table'])) {
                    $selected_table = $_GET['view-table'];
                ?>
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
                                        <input type='hidden' name='view-table' value='<?php echo htmlspecialchars($_GET['view-table']); ?>'>
                                        <label for='search'>Search for keyword:</label>
                                        <input type='text' id='filter-search-box' name='search'
                                            placeholder='Enter keyword... (e.g., Maria, Employed)'
                                            maxlength='100'>
                                    </form>
                                </div>
                                <div class='modal-footer'>
                                    <button class='btn-apply' type='submit' form='table-filter-form'>Apply</button>
                                </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        
                        if ($selected_table == 'alumni-info') {
                        // --- BUTTON/MODAL FOR ALUMNI-INFO ---
                        ?>
                            <div class='add-data'>
                                <button class='myBtn btn-modal-trigger' data-target='addModal-info'>+ Add Data</button>
                                <div class='modal' id='addModal-info'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h2>Add New Alumni</h2>
                                            <span class='close'>&times;</span>
                                        </div>
                                        <form id='add-form-info' action='<?php echo BASE_URL; ?>admin/data/add-alum-info.php' method='POST'>
                                            <div class='modal-body'>
                                                <label for='add-alum-id'>ID:</label>
                                                <input type='text' id='add-alum-id' name='alum-id' required pattern='\d{4}-\d{5}' title='ID must be in the format NNNN-NNNNN (e.g., 2025-12345)'>
                                                <label for='add-alum-firstName'>First Name:</label>
                                                <input type='text' id='add-alum-firstName' name='alum-firstName' required maxlength='50'>
                                                <label for='add-alum-lastName'>Last Name:</label>
                                                <input type='text' id='add-alum-lastName' name='alum-lastName' required maxlength='50'>
                                                <label for='add-alum-contactInfo'>Email:</label>
                                                <input type='email' id='add-alum-contactInfo' name='alum-contactInfo' required maxlength='100'>
                                                <label for='add-alum-status'>Status:</label>
                                                <select id='add-alum-status' name='alum-status'>
                                                    <?php
                                                    foreach ($all_statuses as $status) {
                                                        echo "<option value='" . htmlspecialchars($status['Status_ID']) . "'>" . htmlspecialchars($status['Status_Name']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn-apply" type="submit" form="add-form-info">Add Alumni</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php
                        } elseif ($selected_table == 'alumni-courses') {
                        // --- BUTTON/MODAL FOR ALUMNI-COURSES (NOW "ADD GRADUATION RECORD") ---
                        ?>
                            <div class='add-data'>
                                <button class='myBtn btn-modal-trigger' data-target='addModal-courses'>+ Add Data</button>
            
                                <div class='modal' id='addModal-courses'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h2>Add Graduation Record</h2>
                                            <span class='close'>&times;</span>
                                        </div>
                                        <form id='add-form-courses' action='<?php echo BASE_URL; ?>admin/data/add-alum-courses.php' method='POST' onsubmit='return validateAlumniSelection()'>
                                            <div class='modal-body'>
                                                
                                                <label for='alum-search-input'>Alum:</label>
                                                <input type='text' id='alum-search-input' list='alumni-list' placeholder='Type or select an alum...' required>

                                                <datalist id='alumni-list'>
                                                    <?php
                                                    foreach ($all_alumni as $alum) {
                                                        $displayName = htmlspecialchars($alum['Alum_LastName']) . ", " . htmlspecialchars($alum['Alum_FirstName']) . " (" . htmlspecialchars($alum['Alum_ID']) . ")";
                                                        echo "<option value='" . $displayName . "' data-id='" . htmlspecialchars($alum['Alum_ID']) . "'>" . $displayName . "</option>";
                                                    }
                                                    ?>
                                                </datalist>

                                                <input type='hidden' id='add-alum-id-courses' name='alum-id' value=''>
                                                
                                                <label for='add-degree-id-courses'>Degree:</label>
                                                <select id='add-degree-id-courses' name='degree-id' required>
                                                    <?php
                                                    foreach ($all_degrees as $degree) {
                                                        echo "<option value='" . htmlspecialchars($degree['Degree_ID']) . "'>" . htmlspecialchars($degree['Degree_Name']) . "</option>";
                                                    }
                                                    ?>
                                                </select>

                                                <label for='add-program-id-courses'>Program:</label>
                                                <select id='add-program-id-courses' name='program-id' required>
                                                    <?php
                                                    foreach ($all_programs as $program) {
                                                        echo "<option value='" . htmlspecialchars($program['Program_ID']) . "'>" . htmlspecialchars($program['Program_Name']) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                
                                                <label for='add-grad-year-courses'>Grad Year:</label>
                                                <select id='add-grad-year-courses' name='grad-year' required>
                                                    <?php
                                                    $currentYear = (int)date("Y");
                                                    $earliestYear = 1970;
                                                    for ($year = $currentYear; $year >= $earliestYear; $year--) {
                                                        echo "<option value='$year'>$year</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn-apply" type="submit" form="add-form-courses">Add Record</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } elseif ($selected_table == 'alumni-employment') {
                        // --- BUTTON/MODAL FOR ALUMNI-EMPLOYMENT ---
                        ?>
                            <div class='add-data'>
                                <button class='myBtn btn-modal-trigger' data-target='addModal-employment'>+ Add Data</button>
                                <div class='modal' id='addModal-employment'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h2>Add New Employment Record</h2>
                                            <span class='close'>&times;</span>
                                        </div>
                                        <form id='add-form-employment' action='<?php echo BASE_URL; ?>admin/data/add-alum-employment.php' method='POST'>
                                            <div class='modal-body'>
                                                <p>(Employment fields would go here...)</p>
                                                <p><strong>Note:</strong> 'add-alum-employment.php' script has not been created yet.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn-apply" type="submit" form="add-form-employment" disabled>Add Record</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } // End of dynamic add button if/elseif
                        ?>

                        <?php } // End of if(isset($_GET['view-table'])) ?>
            </div>

            <div class="table-display">
                <?php
                // (No changes in this section)
                if(isset($_GET['view-table'])) {
                    $selected_table = $_GET['view-table'];
                    
                    if($selected_table == 'alumni-info') {
                        include 'tables/alum-info-view.php';
                    } elseif($selected_table == 'alumni-courses') {
                        include 'tables/alum-courses-view.php';
                    } elseif($selected_table == 'alumni-employment') {
                        include 'tables/alum-employment-view.php';
                    }

                    if (isset($totalPages) && $totalPages > 1) {
                        echo '<div class="pagination">';
                        
                        if ($currentPage > 1) {
                            $prevPage = $currentPage - 1;
                            echo "<a href='?view-table=$selected_table&page=$prevPage&sort=$sort_column_key&order=$sort_order&search=$search_term'>&lt;</a>";
                        }

                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = ($i == $currentPage) ? 'class="active"' : '';
                            echo "<a href='?view-table=$selected_table&page=$i&sort=$sort_column_key&order=$sort_order&search=$search_term' $activeClass>$i</a>";
                        }

                        if ($currentPage < $totalPages) {
                            $nextPage = $currentPage + 1;
                            echo "<a href='?view-table=$selected_table&page=$nextPage&sort=$sort_column_key&order=$sort_order&search=$search_term'>&gt;</a>";
                        }
                        echo '</div>';
                    }
                } else {
                    echo "<p>Please select a table to view its contents.</p>";
                }
                ?>
            </div>
        </div>


        <?php include '../../src/templates/footer.php'; ?>
        
        <script src="<?php echo BASE_URL; ?>js/modal-popup.js?v=1.2"></script>
        
        <script>
            // Find the search input and hidden input
            const alumSearchInput = document.getElementById('alum-search-input');
            const hiddenAlumIdInput = document.getElementById('add-alum-id-courses');
            const dataList = document.getElementById('alumni-list');

            if (alumSearchInput) {
                // Listen for when the user selects a value
                alumSearchInput.addEventListener('input', function(event) {
                    const selectedValue = event.target.value;
                    let selectedId = '';

                    // Loop through the datalist options to find the matching ID
                    if (dataList) {
                        for (const option of dataList.options) {
                            if (option.value === selectedValue) {
                                selectedId = option.getAttribute('data-id');
                                break;
                            }
                        }
                    }
                    
                    // Update the hidden input's value
                    if(hiddenAlumIdInput) {
                        hiddenAlumIdInput.value = selectedId;
                    }
                });

                // Clear hidden input if user clears the text box
                alumSearchInput.addEventListener('change', function(event) {
                    if (event.target.value === "") {
                        if(hiddenAlumIdInput) {
                            hiddenAlumIdInput.value = "";
                        }
                    }
                });
            }

            // Validation function to run before submitting the form
            function validateAlumniSelection() {
                if (hiddenAlumIdInput && hiddenAlumIdInput.value === "") {
                    // Check if the text box has a value but it's not a valid one
                    if (alumSearchInput && alumSearchInput.value !== "") {
                        alert("Invalid alum. Please select a valid alum from the list.");
                    } else {
                        // This will be caught by the 'required' on the search box
                        // but we add it for safety.
                        alert("Please select an alum.");
                    }
                    return false; // Stop form submission
                }
                return true; // Allow form submission
            }
        </script>
    </body>
</html>