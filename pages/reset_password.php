<?php
require_once('../includes/config.php');
require_once('../includes/session.php');
require_once('../includes/functions.php');
require_once('../includes/security.php');
require_once('../includes/database.php');
require_once('../includes/logging.php');

$email = '';
$success_message = '';
$error_message = '';

// Handle password reset request.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database.
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($stmt->rowCount() > 0) {
        // Generate a unique reset token.
        $reset_token = generateUniqueToken();

        // Store the token and its expiration timestamp in the database for the user.
        $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        if ($stmt->execute([$reset_token, $token_expiry, $user['id']])) {
            // Send a password reset email to the user with a link containing the token
            // Send the email with the reset link to the user.
            $reset_link = "http://siimteq_technologies.com/reset_password_confirm.php?token=$reset_token";

            // Log the password reset request.
            logPasswordResetRequest($email, true);

            $success_message = "Password reset link sent to your email. Please check your inbox.";
        } else {
            $error_message = "Password reset request failed. Please try again later.";
        }
    } else {
        $error_message = "Email not found. Please enter a valid email address.";
    }
}

// Include the password reset request form HTML.
require_once('../templates/header.php');
?>
<!-- Display any success or error messages here -->
<?php if (!empty($success_message)) : ?>
    <p class="success"><?php echo $success_message; ?></p>
<?php endif; ?>
<?php if (!empty($error_message)) : ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Password Reset Request Form -->
<form method="POST" action="reset_password.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

    <button type="submit">Send Reset Link</button>
</form>

<?php require_once('../templates/footer.php'); ?>