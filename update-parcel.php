<?php
// Staff action: mark a parcel as retrieved once the student collects it.
// (Weight/size/location editing was removed — receivers now own parcel data.)
include 'db_connect.php';

header('Content-Type: application/json');

$trackingNumber = $_POST['trackingNumber'] ?? '';

if ($trackingNumber === '') {
    echo json_encode(['success' => false, 'error' => 'No tracking number provided']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE parcel SET status = ? WHERE "trackingNumber" = ?');
    $stmt->execute(['retrieved', $trackingNumber]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('Mark retrieved failed: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Could not update parcel.']);
}
