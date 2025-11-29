<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../classes/Auth.php';

Auth::startSession();
Auth::requireAdmin();

header('Content-Type: application/json; charset=utf-8');
try {
    $db = getDB();
    $stmt = $db->query('SELECT id, name, email, role, created_at FROM users WHERE is_approved = 0 ORDER BY created_at ASC');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
