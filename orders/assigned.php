<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

Auth::startSession();
Auth::requireRole('trucker');

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getDB();
    $user = Auth::user();
    $stmt = $db->prepare('SELECT id, num_cars, pickup_point, destination_point, notes, status, created_at, accepted_at FROM orders WHERE trucker_id = :tid ORDER BY accepted_at DESC');
    $stmt->execute([':tid' => $user['id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
