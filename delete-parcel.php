<?php
// delete.php
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_POST['trackingNumber'])) {
    $trackingNumber = $_POST['trackingNumber'];

    try {
        $stmt = $pdo->prepare('DELETE FROM parcel WHERE "trackingNumber" = ?');
        $stmt->execute([$trackingNumber]);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        error_log('Delete parcel failed: ' . $e->getMessage());
        echo json_encode(["success" => false, "error" => "Could not delete parcel."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "No tracking number provided"]);
}
