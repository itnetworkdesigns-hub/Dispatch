<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

Auth::startSession();
Auth::requireRole('supplier');

// Accept JSON or form-encoded POST
$data = [];
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true) ?: [];
} else {
    $data = $_POST;
}

$num_cars = isset($data['num_cars']) ? (int)$data['num_cars'] : 0;
$pickup = trim((string)($data['pickup_point'] ?? ''));
$destination = trim((string)($data['destination_point'] ?? ''));
$notes = trim((string)($data['notes'] ?? ''));

$errors = [];
if ($num_cars <= 0) $errors[] = 'Number of cars must be at least 1.';
if ($pickup === '') $errors[] = 'Pickup point is required.';
if ($destination === '') $errors[] = 'Destination is required.';

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare('INSERT INTO orders (supplier_id, num_cars, pickup_point, destination_point, notes) VALUES (:supplier_id, :num_cars, :pickup, :destination, :notes)');
    $user = Auth::user();
    $stmt->execute([
        ':supplier_id' => $user['id'],
        ':num_cars' => $num_cars,
        ':pickup' => $pickup,
        ':destination' => $destination,
        ':notes' => $notes,
    ]);
    $orderId = (int)$db->lastInsertId();
    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
