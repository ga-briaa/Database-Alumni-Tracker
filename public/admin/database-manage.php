<?php include '../../src/database-config.php'; ?>

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
            <div class="select-table">
                <button class="myBtn" id="myBtn">Select Table</button>

                <div class="modal" id="modal">
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
                            <button class="apply-btn" type="submit" form="table-select-form">Apply</button>
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