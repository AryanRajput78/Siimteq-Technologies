<?php
require_once('../includes/config.php');
require_once('../includes/session.php');
require_once('../includes/functions.php');
require_once('../includes/security.php');
require_once('../includes/database.php');
require_once('../includes/logging.php');

// Check if the user is logged in, otherwise redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data based on the user ID from the session.
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logEvent("LOGOUT", "User logged out: Username: " . $user['username'],  "login");

    // Destroy the session and redirect to the login page.
    session_destroy();
    header('Location: login.php');
    exit();
}

require_once('../templates/header.php');
?>
<!-- Greeting -->
<h1>Welcome, <?php echo $user['username']; ?>!</h1>

<!-- Logout Button -->
<form method="POST" action="dashboard.php">
    <button type="submit" name="logout">Logout</button>
</form>

<?php require_once('../templates/footer.php'); ?>