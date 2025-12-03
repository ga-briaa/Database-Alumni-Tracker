<?php
session_start(); // MUST BE INCLUDED TO SAVE LOGIN INFO !

// --- ADMIN AUTHENTICATION CHECK ---
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    // If not logged in as admin, redirect to login page
    header("Location: " . BASE_URL . "login.php");
    exit();
}

include '../../src/database-config.php';

// --- FETCH DROPDOWN DATA ---
// 1. Statuses
$status_sql = "SELECT Status_ID, Status_Name FROM status ORDER BY Status_Name";
$status_result = $conn->query($status_sql);
$all_statuses = [];
if ($status_result->num_rows > 0) {
    while($status_row = $status_result->fetch_assoc()) {
        $all_statuses[] = $status_row;
    }
}

// 2. Degrees
$degree_sql = "SELECT Degree_ID, Degree_Name FROM degree ORDER BY Degree_Name";
$degree_result = $conn->query($degree_sql);
$all_degrees = [];
if ($degree_result->num_rows > 0) {
    while($degree_row = $degree_result->fetch_assoc()) {
        $all_degrees[] = $degree_row;
    }
}

// 3. Colleges (Needed for Program dropdown)
$college_sql = "SELECT College_ID, College_Name FROM college ORDER BY College_Name ASC";
$college_result = $conn->query($college_sql);
$all_colleges = [];
if ($college_result->num_rows > 0) {
    while ($college_row = $college_result->fetch_assoc()) {
        $all_colleges[] = $college_row;
    }
}

// 4. Programs
$program_sql = "SELECT Program_ID, Program_Name FROM program ORDER BY Program_Name";
$program_result = $conn->query($program_sql);
$all_programs = [];
if ($program_result->num_rows > 0) {
    while($program_row = $program_result->fetch_assoc()) {
        $all_programs[] = $program_row;
    }
}

// 5. Positions (For Employment)
$position_sql = "SELECT Position_ID, Position_Name FROM job_position ORDER BY Position_Name";
$position_result = $conn->query($position_sql);
$all_positions = [];
if ($position_result->num_rows > 0) {
    while($row = $position_result->fetch_assoc()) {
        $all_positions[] = $row;
    }
}

// 6. Companies (For Employment)
$company_sql = "SELECT Company_ID, Company_Name FROM company ORDER BY Company_Name";
$company_result = $conn->query($company_sql);
$all_companies = [];
if ($company_result->num_rows > 0) {
    while($row = $company_result->fetch_assoc()) {
        $all_companies[] = $row;
    }
}

// 7. Locations (For Employment)
$location_sql = "SELECT Location_ID, City, Country FROM location ORDER BY Country, City";
$location_result = $conn->query($location_sql);
$all_locations = [];
if ($location_result->num_rows > 0) {
    while($row = $location_result->fetch_assoc()) {
        $all_locations[] = $row;
    }
}

// 8. All Alumni (For Dropdowns)
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
                <!-- SELECT TABLE DROPDOWN -->
                <div class="select-table">
                    <form id="table-select-form" action="" method="GET">
                        <label for="view-table-select">Select Table:</label>
                        <select id="view-table-select" name="view-table" onchange="this.form.submit()">
                            <option value="alumni-info" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'alumni-info') echo 'selected'; ?>>Alumni Information</option>
                            <option value="alumni-courses" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'alumni-courses') echo 'selected'; ?>>Alumni's Courses</option>
                            <option value="alumni-employment" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'alumni-employment') echo 'selected'; ?>>Alumni's Employment</option>
                            <option value="program" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'program') echo 'selected'; ?>>Program</option>
                            <option value="college" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'college') echo 'selected'; ?>>College</option>
                            <option value="degree" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'degree') echo 'selected'; ?>>Degree</option>
                            <option value="status" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'status') echo 'selected'; ?>>Status</option>
                            <option value="company" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'company') echo 'selected'; ?>>Company</option>
                            <option value="location" <?php if(isset($_GET['view-table']) && $_GET['view-table'] == 'location') echo 'selected'; ?>>Location</option>
                        </select>
                    </form>
                </div>
    
                <!-- FILTER, SEARCH, & ADD BUTTONS -->
                <?php
                if(isset($_GET['view-table'])) {
                    $selected_table = $_GET['view-table'];
                ?>
                    <!-- SEARCH BOX -->
                    <div class='search-table'>
                        <form id='table-search-form' action='' method='GET'>
                            <input type='hidden' name='view-table' value='<?php echo isset($_GET['view-table']) ? htmlspecialchars($_GET['view-table']) : ""; ?>'>

                            <?php if(isset($_GET['filter-alum-status'])): ?>
                            <input type="hidden" name="filter-alum-status" value="<?php echo htmlspecialchars($_GET['filter-alum-status']); ?>">
                            <?php endif; ?>

                            <label for='search-box' class='visually-hidden'>Search:</label>
                            <input type='text' id='search-box' name='search' value='<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ""; ?>' placeholder='Search...' maxlength='100' autocomplete='off'>
                        </form>
                    </div>

                    <?php
                    // --- 1. FILTER ALUMNI INFO MODAL ---
                    if ($selected_table == 'alumni-info') {
                    ?>
                        <div class='filter-table'>
                            <button class='myBtn btn-modal-trigger' data-target='filterModal-info'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                </svg>
                            </button>
                            <div class='modal' id='filterModal-info'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Filter Alumni Information</h2>
                                        <span class='close'>&times;</span>
                                    </div>

                                    <form id='filter-form-info' action='' method='GET'>
                                        <div class='modal-body modal-form-grid'>
                                            <input type='hidden' name='view-table' value='<?php echo isset($_GET['view-table']) ? htmlspecialchars($_GET['view-table']) : ""; ?>'>

                                            <?php if(isset($_GET['search'])): ?>
                                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                                            <?php endif; ?>

                                            <label for='filter-alum-status'>Status:</label>
                                            <select id='filter-alum-status' name='filter-alum-status' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Status --</option>
                                                <?php
                                                foreach ($all_statuses as $status) {
                                                    echo "<option value='" . htmlspecialchars($status['Status_ID']) . "'>" . htmlspecialchars($status['Status_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-apply" type="submit" form="filter-form-info">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- REMOVE FILTER -->
                        <?php
                        $hasSearch = isset($_GET['search']) && !empty($_GET['search']);
                        $hasStatusFilter = isset($_GET['filter-alum-status']) && !empty($_GET['filter-alum-status']);

                        if ($hasSearch || $hasStatusFilter) {
                        ?>
                            <div class='remove-filter'>
                                <a href='?view-table=<?php echo htmlspecialchars($selected_table); ?>'
                                    class='myBtn'
                                    title='Remove Filters and Search'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                    </svg>
                                </a>
                            </div>
                        <?php
                        }

                    // --- 2. FILTER ALUMNI COURSES MODAL ---
                    } elseif ($selected_table == 'alumni-courses') {
                    ?>
                        <div class='filter-table'>
                            <button class='myBtn btn-modal-trigger' data-target='filterModal-courses'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                </svg>
                            </button>
                            <div class='modal' id='filterModal-courses'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Filter Alumni Courses</h2>
                                        <span class='close'>&times;</span>
                                    </div>

                                    <form id='filter-form-courses' action='' method='GET'>
                                        <div class='modal-body modal-form-grid'>
                                            <input type='hidden' name='view-table' value='<?php echo isset($_GET['view-table']) ? htmlspecialchars($_GET['view-table']) : ""; ?>'>

                                            <?php if(isset($_GET['search'])): ?>
                                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                                            <?php endif; ?>

                                            <label for='filter-degree-id-courses'>Degree:</label>
                                            <select id='filter-degree-id-courses' name='degree-id' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Degree --</option>
                                                <?php
                                                foreach ($all_degrees as $degree) {
                                                    echo "<option value='" . htmlspecialchars($degree['Degree_ID']) . "'>" . htmlspecialchars($degree['Degree_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='filter-program-id-courses'>Program:</label>
                                            <select id='filter-program-id-courses' name='program-id' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Program --</option>
                                                <?php
                                                foreach ($all_programs as $program) {
                                                    echo "<option value='" . htmlspecialchars($program['Program_ID']) . "'>" . htmlspecialchars($program['Program_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='filter-grad-year-courses'>Graduation Year:</label>
                                            <select id='filter-grad-year-courses' name='grad-year' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Graduation Year --</option>
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
                                            <button class="btn-apply" type="submit" form="filter-form-courses">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- REMOVE FILTER -->
                        <?php
                        $hasSearch = isset($_GET['search']) && !empty($_GET['search']);
                        $hasDegreeFilter = isset($_GET['degree-id']) && !empty($_GET['degree-id']);
                        $hasProgramFilter = isset($_GET['program-id']) && !empty($_GET['program-id']);
                        $hasGradYearFilter = isset($_GET['grad-year']) && !empty($_GET['grad-year']);

                        if ($hasSearch || $hasDegreeFilter || $hasProgramFilter || $hasGradYearFilter) {
                        ?>
                            <div class='remove-filter'>
                                <a href='?view-table=<?php echo htmlspecialchars($selected_table); ?>'
                                    class='myBtn'
                                    title='Remove Filters and Search'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                    </svg>
                                </a>
                            </div>
                        <?php
                        }

                    // --- 3. FILTER ALUMNI EMPLOYMENT MODAL ---
                    } elseif ($selected_table == 'alumni-employment') {
                    ?>
                    <div class='filter-table'>
                            <button class='myBtn btn-modal-trigger' data-target='filterModal-employment'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                </svg>
                            </button>
                            <div class='modal' id='filterModal-employment'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Filter Alumni Employment</h2>
                                        <span class='close'>&times;</span>
                                    </div>

                                    <form id='filter-form-employment' action='' method='GET'>
                                        <div class='modal-body modal-form-grid'>
                                            <input type='hidden' name='view-table' value='<?php echo isset($_GET['view-table']) ? htmlspecialchars($_GET['view-table']) : ""; ?>'>

                                            <?php if(isset($_GET['search'])): ?>
                                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                                            <?php endif; ?>

                                            <label for='filter-position-id'>Position:</label>
                                            <select id='filter-position-id' name='position-id' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Position --</option>
                                                <?php
                                                foreach ($all_positions as $position) {
                                                    echo "<option value='" . htmlspecialchars($position['Position_ID']) . "'>" . htmlspecialchars($position['Position_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='filter-company-id'>Company:</label>
                                            <select id='filter-company-id' name='company-id' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Company --</option>
                                                <?php
                                                foreach ($all_companies as $company) {
                                                    echo "<option value='" . htmlspecialchars($company['Company_ID']) . "'>" . htmlspecialchars($company['Company_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='filter-location-id'>Location:</label>
                                            <select id='filter-location-id' name='location-id' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select Location --</option>
                                                <?php
                                                foreach ($all_locations as $location) {
                                                    echo "<option value='" . htmlspecialchars($location['Location_ID']) . "'>" . htmlspecialchars($location['City']) . ", " . htmlspecialchars($location['Country']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <!-- <label for='filter-start-date'>Start Date:</label>
                                            <input type='date' id='filter-start-date' name='start-date' class='modal-input-field'>

                                            <label for='filter-end-date'>End Date:</label>
                                            <input type='date' id='filter-end-date' name='end-date' class='modal-input-field'> -->
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-apply" type="submit" form="filter-form-employment">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- REMOVE FILTER -->
                        <?php
                        $hasSearch = isset($_GET['search']) && !empty($_GET['search']);
                        $hasPositionFilter = isset($_GET['position-id']) && !empty($_GET['position-id']);
                        $hasCompanyFilter = isset($_GET['company-id']) && !empty($_GET['company-id']);
                        $hasLocationFilter = isset($_GET['location-id']) && !empty($_GET['location-id']);
                        // $hasStartDateFilter = isset($_GET['start-date']) && !empty($_GET['start-date']);
                        // $hasEndDateFilter = isset($_GET['end-date']) && !empty($_GET['end-date']);

                        if ($hasSearch || $hasPositionFilter || $hasCompanyFilter || $hasLocationFilter) {
                        ?>
                            <div class='remove-filter'>
                                <a href='?view-table=<?php echo htmlspecialchars($selected_table); ?>'
                                    class='myBtn'
                                    title='Remove Filters and Search'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                    </svg>
                                </a>
                            </div>
                        <?php
                        }
                    // --- 4. FILTER PROGRAM MODAL ---
                    } elseif ($selected_table == 'program') {
                    ?>
                        <div class='filter-table'>
                            <button class='myBtn btn-modal-trigger' data-target='filterModal-program'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                </svg>
                            </button>
                            <div class='modal' id='filterModal-program'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Filter Program</h2>
                                        <span class='close'>&times;</span>
                                    </div>

                                    <form id='filter-form-program' action='' method='GET'>
                                        <div class='modal-body modal-form-grid'>
                                            <input type='hidden' name='view-table' value='<?php echo isset($_GET['view-table']) ? htmlspecialchars($_GET['view-table']) : ""; ?>'>

                                            <?php if(isset($_GET['search'])): ?>
                                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                                            <?php endif; ?>

                                            <label for='filter-program-college'>College:</label>
                                            <select id='filter-program-college' name='program-college' class='modal-input-field'>
                                                <option value="" disabled selected>-- Select College --</option>
                                                <?php
                                                foreach ($all_colleges as $college) {
                                                    echo "<option value='" . htmlspecialchars($college['College_ID']) . "'>" . htmlspecialchars($college['College_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-apply" type="submit" form="filter-form-program">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- REMOVE FILTER -->
                        <?php
                        $hasSearch = isset($_GET['search']) && !empty($_GET['search']);
                        $hasCollegeFilter = isset($_GET['program-college']) && !empty($_GET['program-college']);

                        if ($hasSearch || $hasCollegeFilter) {
                        ?>
                            <div class='remove-filter'>
                                <a href='?view-table=<?php echo htmlspecialchars($selected_table); ?>'
                                    class='myBtn'
                                    title='Remove Filters and Search'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                                    </svg>
                                </a>
                            </div>
                        <?php
                        }
                    }
                    ?>

                    <?php
                    // --- 1. ALUMNI INFO MODAL ---
                    if ($selected_table == 'alumni-info') {
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
                                        <div class='modal-body modal-form-grid'>
                                            <label for='add-alum-id'>ID:</label>
                                            <input type='text' id='add-alum-id' name='alum-id' required pattern='^\d{4}-\d{5}$' title='ID must be in the format XXXX-XXXXX (e.g., 2025-12345)' maxlength='10' class='modal-input-field'>
                                            <label for='add-alum-firstName'>First Name:</label>
                                            <input type='text' id='add-alum-firstName' name='alum-firstName' required maxlength='50' class='modal-input-field'>
                                            <label for='add-alum-lastName'>Last Name:</label>
                                            <input type='text' id='add-alum-lastName' name='alum-lastName' required maxlength='50' class='modal-input-field'>
                                            <label for='add-alum-contactInfo'>Email:</label>
                                            <input type='email' id='add-alum-contactInfo' name='alum-contactInfo' required maxlength='30' class='modal-input-field' pattern='[^@\s]+@[^@\s]+'>
                                            <label for='add-alum-status'>Status:</label>
                                            <select id='add-alum-status' name='alum-status' class='modal-input-field'>
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
                    // --- 2. ALUMNI COURSES MODAL ---
                    } elseif ($selected_table == 'alumni-courses') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-courses'>+ Add Data</button>
                            <div class='modal' id='addModal-courses'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Add Graduation Record</h2>
                                        <span class='close'>&times;</span>
                                    </div>
                                    <form id='add-form-courses' action='<?php echo BASE_URL; ?>admin/data/add-alum-courses.php' method='POST'>
                                        <div class='modal-body modal-form-grid'>
                                            <label for='add-alum-id-courses'>Alumni:</label>
                                            <select id='add-alum-id-courses' name='alum-id' required class='modal-input-field'>
                                                <option value="" disabled selected>Select an Alum...</option>
                                                <?php
                                                foreach ($all_alumni as $alum) {
                                                    $displayName = htmlspecialchars($alum['Alum_LastName']) . ", " . htmlspecialchars($alum['Alum_FirstName']) . " (" . htmlspecialchars($alum['Alum_ID']) . ")";
                                                    echo "<option value='" . htmlspecialchars($alum['Alum_ID']) . "'>" . $displayName . "</option>";
                                                }
                                                ?>
                                            </select>
                                                    
                                            <label for='add-degree-id-courses'>Degree:</label>
                                            <select id='add-degree-id-courses' name='degree-id' required class='modal-input-field'>
                                                <?php
                                                foreach ($all_degrees as $degree) {
                                                    echo "<option value='" . htmlspecialchars($degree['Degree_ID']) . "'>" . htmlspecialchars($degree['Degree_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='add-program-id-courses'>Program:</label>
                                            <select id='add-program-id-courses' name='program-id' required class='modal-input-field'>
                                                <?php
                                                foreach ($all_programs as $program) {
                                                    echo "<option value='" . htmlspecialchars($program['Program_ID']) . "'>" . htmlspecialchars($program['Program_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                                    
                                            <label for='add-grad-year-courses'>Graduation Year:</label>
                                            <select id='add-grad-year-courses' name='grad-year' required class='modal-input-field'>
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
                    // --- 3. ALUMNI EMPLOYMENT MODAL ---
                    } elseif ($selected_table == 'alumni-employment') {
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
                                        <div class='modal-body modal-form-grid'>
                                            <label for='add-alum-id-employment'>Alumni:</label>
                                            <select id='add-alum-id-employment' name='alum-id' required class='modal-input-field'>
                                                <option value="" disabled selected>Select an Alumni...</option>
                                                <?php
                                                foreach ($all_alumni as $alum) {
                                                    $displayName = htmlspecialchars($alum['Alum_LastName']) . ", " . htmlspecialchars($alum['Alum_FirstName']) . " (" . htmlspecialchars($alum['Alum_ID']) . ")";
                                                    echo "<option value='" . htmlspecialchars($alum['Alum_ID']) . "'>" . $displayName . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='add-position-id'>Position:</label>
                                            <select id='add-position-id' name='position-id' required class='modal-input-field'>
                                                <?php
                                                foreach ($all_positions as $position) {
                                                    echo "<option value='" . htmlspecialchars($position['Position_ID']) . "'>" . htmlspecialchars($position['Position_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='add-company-id'>Company:</label>
                                            <select id='add-company-id' name='company-id' required class='modal-input-field'>
                                                <?php
                                                foreach ($all_companies as $company) {
                                                    echo "<option value='" . htmlspecialchars($company['Company_ID']) . "'>" . htmlspecialchars($company['Company_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='add-location-id'>Location:</label>
                                            <select id='add-location-id' name='location-id' required class='modal-input-field'>
                                                <?php
                                                foreach ($all_locations as $location) {
                                                    echo "<option value='" . htmlspecialchars($location['Location_ID']) . "'>" . htmlspecialchars($location['City']) . ", " . htmlspecialchars($location['Country']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for='add-start-date'>Start Date:</label>
                                            <input type='date' id='add-start-date' name='start-date' required class='modal-input-field'>
                                            <label for='add-end-date'>End Date:</label>
                                            <div class="input-wrapper">
                                                <input type='date' id='add-end-date' name='end-date' title="Leave empty if Current" class='modal-input-field'>
                                                <span class="input-hint">Leave empty if Current</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-apply" type="submit" form="add-form-employment">Add Record</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    // --- 4. PROGRAM MODAL ---
                    } elseif ($selected_table == 'program') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-program'>+ Add Data</button>
                            <div class='modal' id='addModal-program'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h2>Add New Program</h2>
                                        <span class='close'>&times;</span>
                                    </div>
                                    <form id='add-form-program' action='<?php echo BASE_URL; ?>admin/data/add-program.php' method='POST'>
                                        <div class='modal-body modal-form-grid'>
                                            <label for='add-program-id'>Program ID:</label>
                                            <input type='text' id='add-program-id' name='program-id' required maxlength='10' class='modal-input-field'>
                                            <label for='add-program-name'>Program Name:</label>
                                            <input type='text' id='add-program-name' name='program-name' required maxlength='40' class='modal-input-field'>
                                            <label for='add-program-college'>College:</label>
                                            <select id='add-program-college' name='program-college' class='modal-input-field'>
                                                <?php
                                                // This will be populated from the $all_colleges array fetched above
                                                foreach ($all_colleges as $college) {
                                                    echo "<option value='" . htmlspecialchars($college['College_ID']) . "'>" . htmlspecialchars($college['College_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn-apply" type="submit" form="add-form-program">Add Program</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    // --- 5. COLLEGE MODAL ---
                    } elseif ($selected_table == 'college') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-college'>+ Add Data</button>
                        </div>
                    <?php
                    // --- 6. DEGREE MODAL ---
                    } elseif ($selected_table == 'degree') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-degree'>+ Add Data</button>
                        </div>
                    <?php
                    // --- 7. STATUS MODAL ---
                    } elseif ($selected_table == 'status') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-status'>+ Add Data</button>
                        </div>
                    <?php
                    // --- 8. COMPANY MODAL ---
                    } elseif ($selected_table == 'company') {
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-company'>+ Add Data</button>
                        </div>
                    <?php } elseif ($selected_table == 'location') { 
                    ?>
                        <div class='add-data'>
                            <button class='myBtn btn-modal-trigger' data-target='addModal-location'>+ Add Data</button>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="table-display">
                <?php
                if(isset($_GET['view-table'])) {
                    $selected_table = $_GET['view-table'];
                    
                    if($selected_table == 'alumni-info') {
                        include 'tables/alum-info-view.php';
                    } elseif($selected_table == 'alumni-courses') {
                        include 'tables/alum-courses-view.php';
                    } elseif($selected_table == 'alumni-employment') {
                        include 'tables/alum-employment-view.php';
                    } elseif($selected_table == 'program') {
                        include 'tables/program-view.php';
                    } elseif($selected_table == 'college') {
                        include 'tables/college-view.php';
                    } elseif($selected_table == 'degree') {
                        include 'tables/degree-view.php';
                    } elseif($selected_table == 'status') {
                        include 'tables/status-view.php';
                    } elseif($selected_table == 'company') {
                        include 'tables/company-view.php';
                    } elseif($selected_table == 'location') {
                        include 'tables/location-view.php';
                    }

                    // --- PAGINATION ---
                    if (isset($totalPages) && $totalPages > 1) {
                        echo '<div class="pagination">';

                        // Search, and Sort parameters
                        $currentSearch = isset($_GET['search']) ? $_GET['search'] : '';
                        $currentSort = isset($_GET['sort']) ? $_GET['sort'] : '';
                        $currentOrder = isset($_GET['order']) ? $_GET['order'] : '';

                        // Specific Filters
                        $filterStatus = isset($_GET['filter-alum-status']) ? $_GET['filter-alum-status'] : '';
                        $filterDegree = isset($_GET['degree-id']) ? $_GET['degree-id'] : '';
                        $filterProgram = isset($_GET['program-id']) ? $_GET['program-id'] : '';
                        $filterGradYear = isset($_GET['grad-year']) ? $_GET['grad-year'] : '';
                        $filterPosition = isset($_GET['position-id']) ? $_GET['position-id'] : '';
                        $filterCompany = isset($_GET['company-id']) ? $_GET['company-id'] : '';
                        $filterLocation = isset($_GET['location-id']) ? $_GET['location-id'] : '';
                        $filterStartDate = isset($_GET['start-date']) ? $_GET['start-date'] : '';
                        $filterEndDate = isset($_GET['end-date']) ? $_GET['end-date'] : '';

                        // Reconstruct current URL parameters
                        $sortParams = "&sort=$currentSort&order=$currentOrder&search=$currentSearch";

                        // Append filter parameters to URL
                        if (!empty($filterStatus)) $sortParams .= "&filter-alum-status=$filterStatus";
                        if (!empty($filterDegree)) $sortParams .= "&degree-id=$filterDegree";
                        if (!empty($filterProgram)) $sortParams .= "&program-id=$filterProgram";
                        if (!empty($filterGradYear)) $sortParams .= "&grad-year=$filterGradYear";
                        if (!empty($filterPosition)) $sortParams .= "&position-id=$filterPosition";
                        if (!empty($filterCompany)) $sortParams .= "&company-id=$filterCompany";
                        if (!empty($filterLocation)) $sortParams .= "&location-id=$filterLocation";
                        if (!empty($filterStartDate)) $sortParams .= "&start-date=$filterStartDate";
                        if (!empty($filterEndDate)) $sortParams .= "&end-date=$filterEndDate";
                        
                        if ($currentPage > 1) {
                            $prevPage = $currentPage - 1;
                            echo "<a href='?view-table=$selected_table&page=$prevPage$sortParams'>&lt;</a>";
                        }

                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = ($i == $currentPage) ? 'class="active"' : '';
                            echo "<a href='?view-table=$selected_table&page=$i$sortParams' $activeClass>$i</a>";
                        }

                        if ($currentPage < $totalPages) {
                            $nextPage = $currentPage + 1;
                            echo "<a href='?view-table=$selected_table&page=$nextPage$sortParams'>&gt;</a>";
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
        
        <script src="<?php echo BASE_URL; ?>js/modal-popup.js"></script>
        <script src="<?php echo BASE_URL; ?>js/alum-id-validation.js"></script>
        <script src="<?php echo BASE_URL; ?>js/date-validation.js"></script>
    </body>
</html>
<!-- Duplicate ID Error Modal -->
<div class="modal" id="duplicateIdErrorModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Duplicate ID Entry</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>The ID you entered is already taken. Please use a different one.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-apply close">OK</button>
        </div>
    </div>
</div>