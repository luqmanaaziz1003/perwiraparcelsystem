<?php

include 'db_connect.php';

header('Content-Type: application/json'); // Set response type to JSON

$trackingNumber = $_GET['tracking'] ?? '';

if (empty($trackingNumber)) {
    echo json_encode(["status" => "error", "message" => "Tracking number is required."]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM parcel WHERE "trackingNumber" = ?');
    $stmt->execute([$trackingNumber]);
    $parcel = $stmt->fetch();
} catch (PDOException $e) {
    error_log('Parcel lookup failed: ' . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error."]);
    exit;
}

if ($parcel) {
    echo json_encode(["status" => "success", "data" => $parcel]);
} else {
    echo json_encode(["status" => "not_found", "message" => "Parcel not found."]);
}
