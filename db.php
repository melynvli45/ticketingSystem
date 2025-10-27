<?php
// db.php - simple PDO connection for the Ticketing System
// Adjust the configuration below to match your environment.

$dbConfig = [
    'host' => '127.0.0.1',
    'dbname' => 'tixpop',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
];

$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], $options);
} catch (PDOException $e) {
    // In production, do not echo errors. Log them instead.
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Usage: include 'db.php'; then use $pdo

