<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalTracking = $_POST['originalTrackingNumber'];
    $trackingNumber = $_POST['trackingNumber'];
    $ICNo = $_POST['ICNo'];
    $weight = $_POST['weight'];
    $size = $_POST['size'];
    $location = $_POST['deliveryLocation'];

    $sql = "UPDATE parcel SET 
                trackingNumber = ?, 
                ICNo = ?, 
                weight = ?, 
                size = ?, 
                deliveryLocation = ? 
            WHERE trackingNumber = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdsss", $trackingNumber, $ICNo, $weight, $size, $location, $originalTracking);

    if (mysqli_stmt_execute($stmt)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
