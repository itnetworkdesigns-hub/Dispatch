<?php
require_once __DIR__ . '/../config.php';

$pdo = getDB();
$userModel = new User($pdo);

// Example usage - in production, validate and sanitize inputs
try {
    $id = $userModel->register('Alice Trucker', 'alice@example.com', 'securePass123', 'trucker');
    if ($id === false) {
        echo "Email already registered\n";
    } else {
        echo "Registered user id: $id\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
