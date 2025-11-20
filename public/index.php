<?php 
session_start(); // MUST BE INCLUDED TO SAVE LOGIN INFO !

require_once __DIR__ . '/../src/database-config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../src/templates/head.php'; ?>
        <title>DU - Alumni Tracker</title>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/index.css">
    </head>

    <body>
        <!-- Header -->
        <?php include '../src/templates/header.php'; ?>

        <div class="card-container">
            <!-- Section Card: Welcome -->
            <div class="section-card">
                <header>
                    <!-- Alumni changes to which user is logged in, or if admin is logged in -->
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
                    } else {
                        echo "<h1>Welcome, Alumni!</h1>";
                    }
                    ?>
                </header>
    
                <div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In consequat ullamcorper luctus. Nullam a pulvinar nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin tempus est, ac vehicula massa dapibus vitae. Donec bibendum et orci nec hendrerit. Ut ullamcorper risus vitae magna ultricies efficitur. Vivamus congue ullamcorper urna, at gravida leo facilisis vitae. Etiam rutrum nunc in lacus tempor luctus. Etiam placerat interdum velit eget ultrices. Suspendisse dapibus, diam non lobortis efficitur, metus nulla vulputate mauris, quis interdum mauris velit sit amet magna. Donec a ullamcorper eros, vel commodo lacus.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In consequat ullamcorper luctus. Nullam a pulvinar nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin tempus est, ac vehicula massa dapibus vitae. Donec bibendum et orci nec hendrerit. Ut ullamcorper risus vitae magna ultricies efficitur. Vivamus congue ullamcorper urna, at gravida leo facilisis vitae. Etiam rutrum nunc in lacus tempor luctus. Etiam placerat interdum velit eget ultrices. Suspendisse dapibus, diam non lobortis efficitur, metus nulla vulputate mauris, quis interdum mauris velit sit amet magna. Donec a ullamcorper eros, vel commodo lacus.</p>
                </div>
            </div>
    
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') { ?>
                <!-- Section Card: Database (accessible to admin only) -->
                <div class="section-card">
                    <header>
                        <h1>Manage Database</h1>
                    </header>
        
                    <div>
                        <p>Manage alumni, courses, status, and employment information.</p>
                        <a href="<?php echo BASE_URL; ?>admin/database-manage.php?view-table=alumni-employment">Go to Manage Database</a>
                    </div>
                </div>
                <?php } ?>
        </div>

        <!-- Footer -->
        <?php include '../src/templates/footer.php'; ?>
    </body>
</html>