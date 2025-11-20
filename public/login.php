<?php
session_start(); // MUST BE INCLUDED TO SAVE LOGIN INFO !

include '../src/database-config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement to prevent SQL injection
    $stmtLogin = $connLogin->prepare("SELECT password FROM userdata WHERE username = ?");
    $stmtLogin->bind_param("s", $username);
    $stmtLogin->execute();
    $result = $stmtLogin->get_result();

    // Check if a matching user was found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];
        
        if ($password === $db_password) {
            // Successful login
            $_SESSION['username'] = $username;
            header("Location: " . BASE_URL . "index.php");
            exit();
        } else {
            // Invalid credentials
            $error = "Invalid username or password.";
        }
    } else {
        // Invalid credentials
        $error = "Invalid username or password.";
    }

    $stmtLogin->close();
}
?>

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

                <?php if (!empty($error)): ?>
                    <p style="color: red; text-align: center; margin-bottom: 10px;">
                        <?php echo $error; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" value=""
                        id="username"
                        name="username"
                        required
                        maxlength="30">
    
                    <label for="password">Password:</label>
                    <input type="password" value=""
                        id="password"
                        name="password"
                        required
                        maxlength="30"
                        minlength="8">
    
                    <div class="btn-selectors">
                        <button class="btn-apply" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../src/templates/footer.php'; ?>
    </body>
</html>