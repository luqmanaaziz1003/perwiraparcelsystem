<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalTracking = $_POST['originalTrackingNumber'];
    $trackingNumber = $_POST['trackingNumber'];
    $ICNo = $_POST['ICNo'];
    $weight = $_POST['weight'];
    $size = $_POST['size'];
    $location = $_POST['deliveryLocation'];

    $sql = 'UPDATE parcel SET
                "trackingNumber" = ?,
                "ICNo" = ?,
                weight = ?,
                size = ?,
                "deliveryLocation" = ?
            WHERE "trackingNumber" = ?';

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$trackingNumber, $ICNo, $weight, $size, $location, $originalTracking]);
        echo "Success";
    } catch (PDOException $e) {
        // Don't echo the driver message — it leaks schema details to the browser.
        error_log('Update parcel failed: ' . $e->getMessage());
        echo "Error: could not update parcel.";
    }
}
