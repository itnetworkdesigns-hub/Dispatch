<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

Auth::startSession();
Auth::requireRole('trucker');

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getDB();
    $stmt = $db->prepare('SELECT o.id, o.num_cars, o.pickup_point, o.destination_point, o.notes, o.status, o.created_at, u.email as supplier_email FROM orders o JOIN users u ON u.id = o.supplier_id WHERE o.trucker_id IS NULL AND o.status = "pending" ORDER BY o.created_at ASC');
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
