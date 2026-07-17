<?php
require 'db_connect.php';

header('Content-Type: application/json');

$tracking = $_GET['tracking'] ?? '';

if (empty($tracking)) {
    echo json_encode(['status' => 'error', 'message' => 'Tracking number is required.']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM parcel WHERE "trackingNumber" = ?');
$stmt->execute([$tracking]);
$parcel = $stmt->fetch();

if ($parcel) {
    echo json_encode(['status' => 'success', 'data' => $parcel]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parcel not found.']);
}
