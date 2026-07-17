<?php

// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
$checkQuery = "SELECT trackingNumber FROM parcel WHERE trackingNumber = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $trackingNumber);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "<script>alert('Tracking number already exists. Please use a unique one.'); window.history.back();</script>";
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Get receiver info from receiver table
$receiverQuery = "SELECT username FROM receiver WHERE ICNo = ?";
$receiverStmt = $conn->prepare($receiverQuery);
$receiverStmt->bind_param("s", $ICNo);
$receiverStmt->execute();
$receiverStmt->bind_result($receiverName);
$receiverStmt->fetch();
$receiverStmt->close();

// Check if ICNo exists
if (empty($receiverName)) {
    echo "<script>alert('IC No not available in receiver database. Please check again.'); window.history.back();</script>";
    $conn->close();
    exit;
}

$name = $receiverName;

$date_received = date('Y-m-d');
$time = date('H:i:s');
$status = 'Pending';

// Insert into parcel table
$sql = "INSERT INTO parcel (trackingNumber, ICNo, date_received, time, status, name, weight, deliveryLocation, size)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "ssssssdss",
    $trackingNumber,
    $ICNo,
    $date_received,
    $time,
    $status,
    $name,
    $weight,
    $deliveryLocation,
    $size
);

if ($stmt->execute()) {
    echo "<script>alert('Parcel added successfully!'); window.location.href='staff-dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
