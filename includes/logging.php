<?php

// Function to keep the logs in a uniform manner.
function logEvent($event, $message, $fileName)
{
    $logMessage = "[" . date("Y-m-d H:i:s") . "] [$event]: $message" . PHP_EOL;

    // Check for if the log file exists or not. And if not found then it creates a new log file.
    if (!file_exists($fileName)) {
        touch($fileName);
    }

    file_put_contents("logs/$fileName.log", $logMessage, FILE_APPEND);
}

// Functions to manage the login logs.
function logLoginAttempt($username, $ip, $success)
{
    $status = $success ? "SUCCESS" : "FAILED";
    $message = "Login attempt by user: $username, IP: $ip, Status: $status";
    logEvent("LOGIN", $message, "login");
}

function logPasswordResetRequest($email, $success)
{
    $status = $success ? "SUCCESS" : "FAILED";
    $message = "Password reset request for email: $email, Status: $status";
    logEvent("PASSWORD_RESET", $message, "login");
}

// Functions to manage New registration logs.
function userRegistration($username, $email)
{
    $time = date('Y-m-d H:i:s');
    $message = "New User Registered: $username, Email: $email, Time: $time";
    logEvent("Registration", $message, "registration");
}
