<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../classes/Auth.php';

Auth::startSession();
Auth::requireAdmin();

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
} else {
    $data = $_POST;
}

$userId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid user_id']);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare('UPDATE users SET is_approved = 1 WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    echo json_encode(['success' => true, 'user_id' => $userId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
