<?php
// Uses the shared connection instead of its own hardcoded credentials.
include 'db_connect.php';

// Validate received POST data
$trackingNumber = isset($_POST['trackingNumber']) ? trim($_POST['trackingNumber']) : null;
$weight = isset($_POST['weight']) ? floatval($_POST['weight']) : null;
$size = isset($_POST['size']) ? trim($_POST['size']) : null;
$deliveryLocation = isset($_POST['deliveryLocation']) ? trim($_POST['deliveryLocation']) : null;
$ICNo = isset($_POST['ICNo']) ? trim($_POST['ICNo']) : null;

if (!$trackingNumber || !$weight || !$size || !$deliveryLocation || !$ICNo) {
    echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
    exit;
}

// Check if tracking number already exists
$checkStmt = $pdo->prepare('SELECT "trackingNumber" FROM parcel WHERE "trackingNumber" = ?');
$checkStmt->execute([$trackingNumber]);

if ($checkStmt->fetch()) {
    echo "<script>alert('Tracking number already exists. Please use a unique one.'); window.history.back();</script>";
    exit;
}

// Get receiver info from receiver table
$receiverStmt = $pdo->prepare('SELECT username FROM receiver WHERE "ICNo" = ?');
$receiverStmt->execute([$ICNo]);
$receiver = $receiverStmt->fetch();

// Check if ICNo exists
if (!$receiver) {
    echo "<script>alert('IC No not available in receiver database. Please check again.'); window.history.back();</script>";
    exit;
}

$name = $receiver['username'];

$date_received = date('Y-m-d');
$time = date('H:i:s');
// Must match the parcel_status enum in the database exactly — 'Pending' with a
// capital P is not a valid value in Postgres.
$status = 'pending';

$sql = 'INSERT INTO parcel ("trackingNumber", "ICNo", date_received, time, status, name, weight, "deliveryLocation", size)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $trackingNumber,
        $ICNo,
        $date_received,
        $time,
        $status,
        $name,
        $weight,
        $deliveryLocation,
        $size,
    ]);
    echo "<script>alert('Parcel added successfully!'); window.location.href='staff-dashboard.php';</script>";
} catch (PDOException $e) {
    error_log('Add parcel failed: ' . $e->getMessage());
    echo "<script>alert('Could not add parcel. Please try again.'); window.history.back();</script>";
}
