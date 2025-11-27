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
$raw = file_get_contents('php://input');
if ($raw && empty($input)) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    send_json(['success' => false, 'error' => 'Email and password are required'], 422);
}

try {
    $pdo = getDB();
    $userModel = new User($pdo);
    $user = $userModel->login($email, $password);
    if ($user === false) {
        send_json(['success' => false, 'error' => 'Invalid credentials'], 401);
    }

    Auth::login($user);
    send_json(['success' => true, 'user' => Auth::user()]);
} catch (Exception $e) {
    send_json(['success' => false, 'error' => $e->getMessage()], 500);
}
