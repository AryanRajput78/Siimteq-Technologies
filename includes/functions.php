<?php
// Function to validate a username.
function validateUsername($username)
{
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

// Function to validate a password (minimum 8 characters).
function validatePassword($password)
{
    return strlen($password) >= 8;
}

// Function to generate a unique salt.
function generateUniqueSalt()
{
    return bin2hex(random_bytes(16));
}

// Function to hash a password with a salt.
function hashPassword($password, $salt)
{
    return password_hash($password, PASSWORD_BCRYPT, ['salt' => $salt]);
}

// Function to verify a password against a hash and salt.
function verifyPassword($password, $hash, $salt)
{
    $newHash = hashPassword($password, $salt);
    return $newHash === $hash;
}

// Function to generate a unique token (for password reset).
function generateUniqueToken()
{
    return bin2hex(random_bytes(32));
}

// Function to log a new user registration.
function logNewUserRegistration($username, $email)
{
    $eventData = "New user registered: Username: $username, Email: $email";
    logEvent("REGISTRATION", $eventData, "register");
}

// Function to log a password reset request.
function logPasswordResetRequest($email, $success)
{
    $result = $success ? "Success" : "Failure";
    $eventData = "Password reset request ($result): Email: $email";
    logEvent("PASSWORD_RESET_REQUEST", $eventData, "login");
}
