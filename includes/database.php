<?php
$dbConfig = [
    'host' => 'your_database_host',
    'dbname' => 'your_database_name',
    'username' => 'your_database_user',
    'password' => 'your_database_password',
    'charset' => 'utf8mb4',
];

// Function to establish a database connection using PDO.
function connectToDatabase()
{
    global $dbConfig;

    try {
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
            $dbConfig['username'],
            $dbConfig['password']
        );

        // Set PDO to throw exceptions on errors.
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Function to execute a SELECT query.
function fetchSingleRow($pdo, $query, $params = [])
{
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to insert a new user into the database.
function insertUser($pdo, $username, $passwordHash, $salt)
{
    $query = "INSERT INTO users (username, password_hash, salt) VALUES (?, ?, ?)";
    $params = [$username, $passwordHash, $salt];

    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}
