<?php
require_once __DIR__ . '/../config.php';

echo "CLI auth test\n";
$pdo = getDB();
$userModel = new User($pdo);

$email = 'cli_test_' . time() . '@local.test';
$password = 'cliPass123!';
$name = 'CLI Tester';
$role = 'trucker';

echo "Registering $email...\n";
$id = $userModel->register($name, $email, $password, $role);
if ($id === false) {
    echo "Register failed: email exists\n";
} else {
    echo "Registered id: $id\n";
}

echo "Attempting login...\n";
$user = $userModel->login($email, $password);
if ($user === false) {
    echo "Login failed\n";
    exit(1);
}

echo "Login success: "; print_r($user);

echo "Testing Auth::login() and Auth::user()\n";
Auth::login($user);
echo "Auth::user(): "; var_export(Auth::user()); echo "\n";

echo "Calling Auth::logout()\n";
Auth::logout();
echo "Auth::user() after logout: "; var_export(Auth::user()); echo "\n";

echo "CLI auth test complete\n";
