<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

Auth::startSession();
Auth::requireRole('trucker');

// Accept POST with order_id (json or form)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
} else {
    $data = $_POST;
}

$orderId = isset($data['order_id']) ? (int)$data['order_id'] : 0;
if ($orderId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid order_id']);
    exit;
}

try {
    $db = getDB();
    // Attempt to claim the order only if it's still unassigned and pending
    $user = Auth::user();
    $stmt = $db->prepare('UPDATE orders SET trucker_id = :trucker_id, status = "accepted", accepted_at = NOW() WHERE id = :id AND trucker_id IS NULL AND status = "pending"');
    $stmt->execute([':trucker_id' => $user['id'], ':id' => $orderId]);
    if ($stmt->rowCount() === 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Order already claimed or not available']);
        exit;
    }
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
