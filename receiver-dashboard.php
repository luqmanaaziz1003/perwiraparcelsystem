<?php

include 'db_connect.php'; // Added missing semicolon

header('Content-Type: application/json'); // Set response type to JSON

$trackingNumber = $_GET['tracking'] ?? '';

if (empty($trackingNumber)) {
    echo json_encode(["status" => "error", "message" => "Tracking number is required."]);
    exit;
}

$sql = "SELECT * FROM parcel WHERE tracking_number = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // If prepare fails
    echo json_encode(["status" => "error", "message" => "Database error: failed to prepare statement."]);
    exit;
}

$stmt->bind_param("s", $trackingNumber);

if (!$stmt->execute()) {
    // If execution fails
    echo json_encode(["status" => "error", "message" => "Database error: failed to execute statement."]);
    exit;
}

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $parcel = $result->fetch_assoc();
    echo json_encode(["status" => "success", "data" => $parcel]);
} else {
    echo json_encode(["status" => "not_found", "message" => "Parcel not found."]);
}

$stmt->close();
$conn->close();
?>
