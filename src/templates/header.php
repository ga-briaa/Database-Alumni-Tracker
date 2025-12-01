<?php require_once __DIR__ . '/../../src/database-config.php'; ?>

<!-- Header Template -->
<header class="header">
    <a href="<?php echo BASE_URL; ?>index.php">
        <img src="<?php echo BASE_URL; ?>assets/DU-Logo-with-text.png" alt="Database University" class="header-logo">
    </a>
    <div class="header-tabs">
        <a href="<?php echo BASE_URL; ?>index.php">Home</a>
        <!-- <a href="<?php echo BASE_URL; ?>survey.php">Survey</a> -->

        <!-- Check if user is logged in, show logout -->
        <?php if (isset($_SESSION['username'])) { ?>
            <a href="<?php echo BASE_URL; ?>logout.php">Logout</a>
        <?php } else { ?>
            <a href="<?php echo BASE_URL; ?>login.php">Login</a>
        <?php } ?>

    </div>
    <div class="header-right-text">
        <p>Database - Alumni Tracker</p>
    </div>
</header>