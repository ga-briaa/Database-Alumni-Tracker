<?php include '../src/database-config.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../src/templates/head.php'; ?>
        <title>DU - Login</title>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/login.css">
    </head>

    <body>
        <!--Header-->
        <?php include '../src/templates/header.php'; ?>

        <div class="card-container">
            <div class="section-card">
                <header>
                    <h1>Login</h1>
                </header>

                <label>Username:</label>
                <input type="text" value=""
                    required
                    maxlength="30">

                <label>Password:</label>
                <input type="password" value=""
                    required
                    maxlength="30"
                    minlength="8">

                <div class="btn-selectors">
                    <button class="btn-apply" type="submit" form="edit-form">Login</button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../src/templates/footer.php'; ?>
    </body>
</html>