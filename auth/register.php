<?php
require_once __DIR__ . '/../config.php';

function send_json($data, int $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(['success' => false, 'error' => 'Invalid request method'], 405);
}

$input = $_POST;
// If JSON body posted
$raw = file_get_contents('php://input');
if ($raw && empty($input)) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = trim($input['role'] ?? 'trucker');

$errors = [];
if ($name === '') {
    $errors[] = 'Name is required';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}
if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters';
}
if (!in_array($role, ['trucker', 'supplier'], true)) {
    $errors[] = 'Invalid role';
}

if ($errors) {
    send_json(['success' => false, 'errors' => $errors], 422);
}

try {
    $pdo = getDB();
    $userModel = new User($pdo);
    $res = $userModel->register($name, $email, $password, $role);
    if ($res === false) {
        send_json(['success' => false, 'error' => 'Email already registered'], 409);
    }

    send_json(['success' => true, 'user_id' => $res]);
} catch (Exception $e) {
    send_json(['success' => false, 'error' => $e->getMessage()], 500);
}
