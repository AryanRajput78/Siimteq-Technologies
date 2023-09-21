<?php
require_once('../includes/config.php');
require_once('../includes/logging.php');
require_once('../includes/session.php');
require_once('../includes/security.php');
require_once('../includes/database.php');
require_once('../includes/functions.php');

// Initialize variables.
$error_message = '';
$login_attempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;

// Handle user login.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database based on the provided username.
    $pdo = connectToDatabase();
    $user = fetchSingleRow($pdo, "SELECT * FROM users WHERE username = ?", [$username]);

    if ($login_attempts >= 3) {
        $error_message = "Account locked due to too many failed login attempts.";
    } else {
        if ($user && verifyPassword($password, $user['password_hash'], $user['salt'])) {
            // Authentication successful, start a secure session.
            session_start();
            $_SESSION['user_id'] = $user['id'];

            // Reset login attempts on successful login.
            $_SESSION['login_attempts'] = 0;

            // Log the successful login attempt.
            logLoginAttempt($username, $_SERVER['REMOTE_ADDR'], true);

            // Redirect to the user to another page.
            header('Location: ');
            exit();
        } else {
            // Authentication failed, log the failed login attempt.
            logLoginAttempt($username, $_SERVER['REMOTE_ADDR'], false);

            // Increment login attempts.
            $_SESSION['login_attempts'] = ++$login_attempts;

            if ($login_attempts >= 3) {
                // Lock the account after three failed attempts.
                $error_message = "Account locked due to too many failed login attempts. Please contact support.";
            } else {
                // Display an error message to the user.
                $error_message = "Invalid username or password. You have " . (3 - $login_attempts) . " attempts remaining.";
            }
        }
    }
}


require_once('../templates/header.php');
?>

<?php if (isset($error_message)) : ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Login Form -->
<form method="POST" action="login.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

<?php require_once('../templates/footer.php'); ?>