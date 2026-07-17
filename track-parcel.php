<?php
require 'db_connect.php';

$tracking = $_GET['tracking'] ?? '';

if (empty($tracking)) {
    echo json_encode(['status' => 'error', 'message' => 'Tracking number is required.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM parcel WHERE trackingNumber = ?");
$stmt->bind_param("s", $tracking);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $parcel = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $parcel]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parcel not found.']);
}

$stmt->close();
$conn->close();
?>
