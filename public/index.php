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

                <?php 
                if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
                        // Display message for admin user
                        echo "<div>
                                <p>Welcome to the DU Alumni Tracker.</p>
                                <p>Manage the database and oversee all alumni records.</p>
                                <p><strong>You have full access to the database.</strong></p>
                            </div>";
                    } else {
                        // Display message for regular alumni user
                        echo "<div>
                                <p>Welcome to the DU Alumni Tracker!</p>
                                <p>This Alumni Tracker is designed to help us stay in touch and celebrate your milestones. We are looking to update our records regarding your profile. Whether you are just starting out, changing careers, or leading a team, your story matters to us. </p>
                                <p>By tracking your <strong>course history, graduation year, and career progress</strong>, you can help enhance our curriculum and support future alumni.</p>
                            </div>";
                    }
                ?>
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