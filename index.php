<?php
require_once('includes/config.php');
require_once('includes/session.php');
require_once('includes/functions.php');
require_once('includes/security.php');
require_once('includes/database.php');
require_once('includes/logging.php');

// Get the requested path from the URL.
$requestURI = $_SERVER['REQUEST_URI'];
$basePath = '/your-project-root';

// Remove the base path from the URL.
$page = str_replace($basePath, '', $requestURI);

// Define allowed pages.
$allowedPages = [
    'login',
    'register',
    'logout',
    'reset_password',
];

// Check if the requested page is allowed.
if (in_array($page, $allowedPages)) {
    require_once("pages/$page.php");
} else {
    require_once('404.php');
}
