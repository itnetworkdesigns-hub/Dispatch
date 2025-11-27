<?php
require_once __DIR__ . '/../config.php';

$pdo = getDB();
$userModel = new User($pdo);

$email = 'alice@example.com';
$password = 'securePass123';

$user = $userModel->login($email, $password);
if ($user === false) {
    echo "Invalid credentials\n";
    exit;
}

Auth::login($user);
echo "Logged in as: " . Auth::user()['name'] . " (" . Auth::user()['role'] . ")\n";
