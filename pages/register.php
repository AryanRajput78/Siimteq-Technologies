<?php
require_once('../includes/config.php');
require_once('../includes/session.php');
require_once('../includes/functions.php');
require_once('../includes/security.php');
require_once('../includes/database.php');
require_once('../includes/logging.php');

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Initialize variables.
$username = '';
$password = '';
$confirm_password = '';
$error_message = '';

// Handle user registration.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate user input.
    if (!validateUsername($username) || !validatePassword($password) || $password !== $confirm_password) {
        $error_message = "Invalid input or passwords do not match.";
    } else {
        // Check if the username is already taken.
        $pdo = connectToDatabase();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error_message = "Username already exists. Please choose another.";
        } else {
            // Generate a unique salt for the user.
            $salt = generateUniqueSalt();

            // Hash the password.
            $hashed_password = hashPassword($password, $salt);

            // Insert the new user into the database.

            if (insertUser($pdo, $username, $hashed_password, $salt)) {
                userRegistration($username, $user['email']);

                header('Location: login.php');
                exit();
            } else {
                $error_message = "Registration failed. Please try again later.";
            }
        }
    }
}

require_once('../templates/header.php');
?>
<?php if (!empty($error_message)) : ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Registration Form -->
<form method="POST" action="register.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br>

    <button type="submit">Register</button>
</form>

<?php require_once('../templates/footer.php'); ?>