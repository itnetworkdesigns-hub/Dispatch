<?php
require_once __DIR__ . '/../config.php';

function send_json($data, int $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// Allow POST for logout (to be CSRF-safe) but accept GET for convenience
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST','GET'], true)) {
    send_json(['success' => false, 'error' => 'Invalid request method'], 405);
}

try {
    Auth::logout();
    send_json(['success' => true]);
} catch (Exception $e) {
    send_json(['success' => false, 'error' => $e->getMessage()], 500);
}
